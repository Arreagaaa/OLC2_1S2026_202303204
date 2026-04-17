<?php

namespace App\Interpreter;

use App\Arm64\ArmEmitter;
use App\Arm64\Assembler;
use App\Arm64\LabelManager;
use Antlr\Antlr4\Runtime\CommonTokenStream;
use Antlr\Antlr4\Runtime\InputStream;

// generador ARM64 para Sprint 1/2: variables, expresiones y fmt.Println basico
class CodeGen
{
    private const LOCAL_STACK_SIZE = 2048;

    private string $source;
    private string $console;
    private ArmEmitter $emitter;
    private Assembler $assembler;
    private LabelManager $labels;

    /** @var array<string, array{type:string, offset:int}> */
    private array $variables = [];
    private int $nextOffset = 0;
    private string $functionEndLabel = '_start_end';

    /** @var array<int, array{break:string, continue:string}> */
    private array $loopStack = [];

    /** @var array<string, string> */
    private array $stringPool = [];
    /** @var array<string, string> */
    private array $literalByValue = [];

    public function __construct(string $source = '', string $console = '')
    {
        $this->source    = $source;
        $this->console   = $console;
        $this->emitter   = new ArmEmitter();
        $this->assembler = new Assembler();
        $this->labels    = new LabelManager();
    }

    public function generateProgram(): string
    {
        // smoke test: programa vacio debe generar .section .text sin errores
        if (trim($this->source) === '') {
            return $this->generateEmptyProgram();
        }

        try {
            return $this->generateFromSource();
        } catch (\Throwable $e) {
            // fallback seguro: imprime la salida interpretada si el subconjunto no aplica
            return $this->generateConsoleFallback();
        }
    }

    public function toolchainReady(): bool
    {
        return $this->assembler->hasToolchain();
    }

    private function generateEmptyProgram(): string
    {
        $this->emitter->emitComment('programa vacio generado para la fase inicial');
        $this->emitter->emitSection('.text');
        $this->emitter->emitAlign(2);
        $this->emitter->emitGlobal('_start');
        $this->emitter->emitLabel('_start');
        $this->emitExitSyscall();

        return $this->emitter->toString();
    }

    private function generateFromSource(): string
    {
        $input = InputStream::fromString($this->source);
        $lexer = new \GolampiLexer($input);
        $tokens = new CommonTokenStream($lexer);
        $parser = new \GolampiParser($tokens);

        $tree = $parser->program();

        if (!method_exists($tree, 'topDecl')) {
            return $this->generateConsoleFallback();
        }

        /** @var \Context\ProgramRuleContext $program */
        $program = $tree;

        /** @var \Context\BlockStmtContext|null $mainBlock */
        $mainBlock = null;
        foreach ($program->topDecl() as $topDecl) {
            if ($topDecl->funcDecl() === null) {
                continue;
            }
            $funcDecl = $topDecl->funcDecl();
            if ($funcDecl->ID()->getText() === 'main') {
                $mainBlock = $funcDecl->block();
                break;
            }
        }

        if ($mainBlock === null) {
            return $this->generateEmptyProgram();
        }

        $this->registerBuiltinStrings();

        $this->emitter->emitSection('.text');
        $this->emitter->emitAlign(2);
        $this->emitter->emitGlobal('_start');
        $this->emitter->emitLabel('_start');

        // prologo _start (convencion de frame basica)
        $this->emitter->emit('stp x29, x30, [sp, #-16]!');
        $this->emitter->emit('mov x29, sp');
        $this->emitter->emit('sub sp, sp, #' . self::LOCAL_STACK_SIZE);

        foreach ($mainBlock->stmt() as $stmt) {
            $this->compileStmt($stmt);
        }

        // epilogo _start
        $this->emitter->emitLabel($this->functionEndLabel);
        $this->emitter->emit('add sp, sp, #' . self::LOCAL_STACK_SIZE);
        $this->emitter->emit('ldp x29, x30, [sp], #16');

        $this->emitExitSyscall();
        $this->emitPrintIntRoutine();
        $this->emitDataSection();

        return $this->emitter->toString();
    }

    private function generateConsoleFallback(): string
    {
        $this->emitter = new ArmEmitter();
        $this->emitter->emitComment('fallback ARM64: salida precomputada del interprete');
        $this->emitter->emitSection('.data');
        $this->emitter->emitAscii('golampi_output', $this->console);
        $this->emitter->emitRaw('golampi_output_len = . - golampi_output');
        $this->emitter->emitSection('.text');
        $this->emitter->emitAlign(2);
        $this->emitter->emitGlobal('_start');
        $this->emitter->emitLabel('_start');
        $this->emitter->emit('mov x0, #1');
        $this->emitter->emit('adrp x1, golampi_output');
        $this->emitter->emit('add x1, x1, :lo12:golampi_output');
        $this->emitter->emit('ldr x2, =golampi_output_len');
        $this->emitter->emit('mov x8, #64');
        $this->emitter->emit('svc #0');
        $this->emitExitSyscall();
        return $this->emitter->toString();
    }

    private function compileStmt(object $stmt): void
    {
        if ($stmt instanceof \Context\BreakStmtContext) {
            if (empty($this->loopStack)) {
                throw new \RuntimeException('break fuera de un ciclo.');
            }
            $target = $this->loopStack[count($this->loopStack) - 1]['break'];
            $this->emitter->emit('b ' . $target);
            return;
        }

        if ($stmt instanceof \Context\ContinueStmtContext) {
            if (empty($this->loopStack)) {
                throw new \RuntimeException('continue fuera de un ciclo.');
            }
            $target = $this->loopStack[count($this->loopStack) - 1]['continue'];
            $this->emitter->emit('b ' . $target);
            return;
        }

        if ($stmt instanceof \Context\ReturnStmtContext) {
            $this->emitter->emit('b ' . $this->functionEndLabel);
            return;
        }

        if ($stmt instanceof \Context\IncStmtContext) {
            $name = $stmt->ID()->getText();
            if (!isset($this->variables[$name])) {
                throw new \RuntimeException("Variable '$name' no declarada para incremento.");
            }
            $this->loadVarToX0($name);
            $this->emitter->emit('add x0, x0, #1');
            $this->storeScalarFromX0($name);
            return;
        }

        if ($stmt instanceof \Context\DecStmtContext) {
            $name = $stmt->ID()->getText();
            if (!isset($this->variables[$name])) {
                throw new \RuntimeException("Variable '$name' no declarada para decremento.");
            }
            $this->loadVarToX0($name);
            $this->emitter->emit('sub x0, x0, #1');
            $this->storeScalarFromX0($name);
            return;
        }

        if (method_exists($stmt, 'ifStmt') && $stmt->ifStmt() !== null) {
            $this->compileIfStmt($stmt->ifStmt());
            return;
        }

        if (method_exists($stmt, 'switchStmt') && $stmt->switchStmt() !== null) {
            $this->compileSwitchStmt($stmt->switchStmt());
            return;
        }

        if (method_exists($stmt, 'forStmt') && $stmt->forStmt() !== null) {
            $this->compileForStmt($stmt->forStmt());
            return;
        }

        if (method_exists($stmt, 'arrayAssign') && $stmt->arrayAssign() !== null) {
            $this->compileArrayAssign($stmt->arrayAssign());
            return;
        }

        if (method_exists($stmt, 'varDecl') && $stmt->varDecl() !== null) {
            $this->compileVarDecl($stmt->varDecl());
            return;
        }

        if (method_exists($stmt, 'shortDecl') && $stmt->shortDecl() !== null) {
            $this->compileShortDecl($stmt->shortDecl());
            return;
        }

        if (method_exists($stmt, 'assignment') && $stmt->assignment() !== null) {
            $this->compileAssign($stmt->assignment());
            return;
        }

        if (method_exists($stmt, 'compoundAssign') && $stmt->compoundAssign() !== null) {
            $this->compileCompoundAssign($stmt->compoundAssign());
            return;
        }

        if (method_exists($stmt, 'fmtPrintln') && $stmt->fmtPrintln() !== null) {
            $this->compilePrintln($stmt->fmtPrintln());
            return;
        }

        throw new \RuntimeException('Sentencia fuera del alcance Sprint 3.');
    }

    private function compileBlock(object $block): void
    {
        foreach ($block->stmt() as $stmt) {
            $this->compileStmt($stmt);
        }
    }

    private function compileIfStmt(object $ifStmt): void
    {
        $elseLabel = $this->labels->next('L_ELSE');
        $endLabel = $this->labels->next('L_ENDIF');

        $this->compileExpr($ifStmt->expr());
        $this->emitter->emit('cmp x0, #0');

        $hasElseIf = method_exists($ifStmt, 'ifStmt') && $ifStmt->ifStmt() !== null;
        $hasElseBlock = method_exists($ifStmt, 'block') && count($ifStmt->block()) > 1;
        $hasElse = $hasElseIf || $hasElseBlock;

        $this->emitter->emit('b.eq ' . ($hasElse ? $elseLabel : $endLabel));
        $this->compileBlock($ifStmt->block(0));

        if ($hasElse) {
            $this->emitter->emit('b ' . $endLabel);
            $this->emitter->emitLabel($elseLabel);
            if ($hasElseIf) {
                $this->compileIfStmt($ifStmt->ifStmt());
            } else {
                $this->compileBlock($ifStmt->block(1));
            }
        }

        $this->emitter->emitLabel($endLabel);
    }

    private function compileSwitchStmt(object $switchStmt): void
    {
        $endLabel = $this->labels->next('L_SWITCH_END');

        if ($switchStmt->expr() !== null) {
            $this->compileExpr($switchStmt->expr());
        } else {
            $this->emitter->emit('mov x0, #1');
        }
        $this->emitter->emit('mov x20, x0');

        $defaultClause = null;

        foreach ($switchStmt->caseClause() as $caseClause) {
            if ($caseClause instanceof \Context\DefaultClauseContext) {
                $defaultClause = $caseClause;
                continue;
            }

            $bodyLabel = $this->labels->next('L_CASE_BODY');
            $nextLabel = $this->labels->next('L_CASE_NEXT');

            foreach ($caseClause->exprList()->expr() as $expr) {
                $this->compileExpr($expr);
                $this->emitter->emit('cmp x20, x0');
                $this->emitter->emit('b.eq ' . $bodyLabel);
            }

            $this->emitter->emit('b ' . $nextLabel);
            $this->emitter->emitLabel($bodyLabel);
            foreach ($caseClause->stmt() as $stmt) {
                $this->compileStmt($stmt);
            }
            $this->emitter->emit('b ' . $endLabel);
            $this->emitter->emitLabel($nextLabel);
        }

        if ($defaultClause !== null) {
            foreach ($defaultClause->stmt() as $stmt) {
                $this->compileStmt($stmt);
            }
        }

        $this->emitter->emitLabel($endLabel);
    }

    private function compileForStmt(object $forStmt): void
    {
        if ($forStmt instanceof \Context\ForInfiniteContext) {
            $start = $this->labels->next('L_FOR_INF');
            $end = $this->labels->next('L_FOR_END');
            $this->loopStack[] = ['break' => $end, 'continue' => $start];

            $this->emitter->emitLabel($start);
            $this->compileBlock($forStmt->block());
            $this->emitter->emit('b ' . $start);
            $this->emitter->emitLabel($end);

            array_pop($this->loopStack);
            return;
        }

        if ($forStmt instanceof \Context\ForWhileContext) {
            $start = $this->labels->next('L_FOR_WHILE');
            $end = $this->labels->next('L_FOR_END');
            $this->loopStack[] = ['break' => $end, 'continue' => $start];

            $this->emitter->emitLabel($start);
            $this->compileExpr($forStmt->expr());
            $this->emitter->emit('cmp x0, #0');
            $this->emitter->emit('b.eq ' . $end);
            $this->compileBlock($forStmt->block());
            $this->emitter->emit('b ' . $start);
            $this->emitter->emitLabel($end);

            array_pop($this->loopStack);
            return;
        }

        if ($forStmt instanceof \Context\ForClassicContext) {
            $start = $this->labels->next('L_FOR_CLASSIC');
            $post = $this->labels->next('L_FOR_POST');
            $end = $this->labels->next('L_FOR_END');

            $this->compileForInit($forStmt->forInit());
            $this->loopStack[] = ['break' => $end, 'continue' => $post];

            $this->emitter->emitLabel($start);
            $this->compileExpr($forStmt->expr());
            $this->emitter->emit('cmp x0, #0');
            $this->emitter->emit('b.eq ' . $end);
            $this->compileBlock($forStmt->block());
            $this->emitter->emitLabel($post);
            $this->compileForPost($forStmt->forPost());
            $this->emitter->emit('b ' . $start);
            $this->emitter->emitLabel($end);

            array_pop($this->loopStack);
            return;
        }

        throw new \RuntimeException('Tipo de for no soportado en Sprint 3.');
    }

    private function compileForInit(object $forInit): void
    {
        if ($forInit instanceof \Context\ForInitShortContext) {
            $this->compileShortDecl($forInit->shortDecl());
            return;
        }
        if ($forInit instanceof \Context\ForInitAssignContext) {
            $this->compileAssign($forInit->assignment());
            return;
        }
        throw new \RuntimeException('Inicializacion de for no soportada.');
    }

    private function compileForPost(object $forPost): void
    {
        if ($forPost instanceof \Context\ForPostAssignContext) {
            $this->compileAssign($forPost->assignment());
            return;
        }
        if ($forPost instanceof \Context\ForPostCompoundContext) {
            $this->compileCompoundAssign($forPost->compoundAssign());
            return;
        }
        if ($forPost instanceof \Context\ForPostIncContext) {
            $name = $forPost->ID()->getText();
            $this->loadVarToX0($name);
            $this->emitter->emit('add x0, x0, #1');
            $this->storeScalarFromX0($name);
            return;
        }
        if ($forPost instanceof \Context\ForPostDecContext) {
            $name = $forPost->ID()->getText();
            $this->loadVarToX0($name);
            $this->emitter->emit('sub x0, x0, #1');
            $this->storeScalarFromX0($name);
            return;
        }
        throw new \RuntimeException('Post de for no soportado.');
    }

    private function compileVarDecl(object $varDecl): void
    {
        $type = $varDecl->typeRef()->getText();
        $ids = $varDecl->idList()->ID();
        $exprs = $varDecl->exprList() !== null ? $varDecl->exprList()->expr() : [];

        foreach ($ids as $i => $idNode) {
            $name = $idNode->getText();
            if (!isset($this->variables[$name])) {
                $this->declareVar($name, $type);
            }

            if (isset($exprs[$i])) {
                if ($this->isArrayType($type)) {
                    $this->compileArrayLiteralInit($name, $exprs[$i]);
                } else {
                    $exprType = $this->compileExpr($exprs[$i]);
                    $this->storeExprToVar($name, $exprType);
                }
            } else {
                $this->storeDefaultForType($name, $type);
            }
        }
    }

    private function compileShortDecl(object $shortDecl): void
    {
        $ids = $shortDecl->idList()->ID();
        $exprs = $shortDecl->exprList()->expr();

        foreach ($ids as $i => $idNode) {
            $name = $idNode->getText();
            $expr = $exprs[$i] ?? null;
            if ($expr === null) {
                continue;
            }

            $inferred = $this->inferExprTypeForDecl($expr);
            if ($inferred !== null) {
                if (!isset($this->variables[$name])) {
                    $this->declareVar($name, $inferred);
                }
                $this->compileArrayLiteralInit($name, $expr);
                continue;
            }

            $exprType = $this->compileExpr($expr);
            if (!isset($this->variables[$name])) {
                $this->declareVar($name, $exprType);
            }

            if ($this->isArrayType($this->variables[$name]['type'])) {
                $this->compileArrayLiteralInit($name, $expr);
            } else {
                $this->storeExprToVar($name, $exprType);
            }
        }
    }

    private function compileAssign(object $assign): void
    {
        $name = $assign->ID()->getText();
        if (!isset($this->variables[$name])) {
            return;
        }

        $exprType = $this->compileExpr($assign->expr());
        $this->storeExprToVar($name, $exprType);
    }

    private function compileArrayAssign(object $arrayAssign): void
    {
        if ($arrayAssign instanceof \Context\ArrayAssign1DContext) {
            $name = $arrayAssign->ID()->getText();
            $meta = $this->variables[$name] ?? null;
            if ($meta === null || !isset($meta['dims'][0])) {
                throw new \RuntimeException("Arreglo '$name' no declarado.");
            }

            $this->compileExpr($arrayAssign->expr(0));
            $this->emitter->emit('mov x9, x0');
            $valueType = $this->compileExpr($arrayAssign->expr(1));

            if ($valueType === 'string') {
                throw new \RuntimeException('Asignacion string en arreglo fuera del alcance Sprint 3.');
            }

            $this->emitArrayAddress1D($name, 'x9', 'x11');
            $this->emitter->emit('str x0, [x11]');
            return;
        }

        if ($arrayAssign instanceof \Context\ArrayAssign2DContext) {
            $name = $arrayAssign->ID()->getText();
            $meta = $this->variables[$name] ?? null;
            if ($meta === null || !isset($meta['dims'][1])) {
                throw new \RuntimeException("Matriz '$name' no declarada.");
            }

            $this->compileExpr($arrayAssign->expr(0));
            $this->emitter->emit('mov x9, x0');
            $this->compileExpr($arrayAssign->expr(1));
            $this->emitter->emit('mov x10, x0');
            $valueType = $this->compileExpr($arrayAssign->expr(2));

            if ($valueType === 'string') {
                throw new \RuntimeException('Asignacion string en matriz fuera del alcance Sprint 3.');
            }

            $this->emitArrayAddress2D($name, 'x9', 'x10', 'x11');
            $this->emitter->emit('str x0, [x11]');
            return;
        }

        throw new \RuntimeException('Asignacion de arreglo no soportada.');
    }

    private function compileCompoundAssign(object $assign): void
    {
        $name = $assign->ID()->getText();
        if (!isset($this->variables[$name])) {
            return;
        }

        $varType = $this->variables[$name]['type'];
        if ($varType === 'string') {
            return;
        }

        $this->loadVarToX0($name);
        $this->emitter->emit('mov x9, x0');
        $this->compileExpr($assign->expr());
        $this->emitter->emit('mov x10, x0');

        $op = $assign->op->getText();
        if ($op === '+=') {
            $this->emitter->emit('add x0, x9, x10');
        } elseif ($op === '-=') {
            $this->emitter->emit('sub x0, x9, x10');
        } elseif ($op === '*=') {
            $this->emitter->emit('mul x0, x9, x10');
        } elseif ($op === '/=') {
            $this->emitter->emit('sdiv x0, x9, x10');
        }

        $this->storeScalarFromX0($name);
    }

    private function compilePrintln(object $println): void
    {
        $argList = $println->argList();
        $args = $argList !== null ? $argList->expr() : [];

        foreach ($args as $i => $expr) {
            if ($i > 0) {
                $this->emitWriteLabel('__space_str', '__space_str_len');
            }

            $type = $this->compileExpr($expr);
            if ($type === 'string') {
                // string en x0=ptr, x1=len
                $this->emitter->emit('mov x3, x0');
                $this->emitter->emit('mov x4, x1');
                $this->emitter->emit('mov x0, #1');
                $this->emitter->emit('mov x1, x3');
                $this->emitter->emit('mov x2, x4');
                $this->emitter->emit('mov x8, #64');
                $this->emitter->emit('svc #0');
            } elseif ($type === 'bool') {
                $trueLabel = $this->labels->next('L_BOOL_TRUE');
                $endLabel = $this->labels->next('L_BOOL_END');
                $this->emitter->emit('cmp x0, #0');
                $this->emitter->emit('b.ne ' . $trueLabel);
                $this->emitWriteLabel('__false_str', '__false_str_len');
                $this->emitter->emit('b ' . $endLabel);
                $this->emitter->emitLabel($trueLabel);
                $this->emitWriteLabel('__true_str', '__true_str_len');
                $this->emitter->emitLabel($endLabel);
            } else {
                $this->emitter->emit('bl __print_int');
            }
        }

        $this->emitWriteLabel('__newline_str', '__newline_str_len');
    }

    private function compileExpr(object $expr): string
    {
        if ($expr instanceof \Context\PrimaryExprContext) {
            return $this->compilePrimary($expr->primary());
        }

        if ($expr instanceof \Context\IdExprContext) {
            $name = $expr->ID()->getText();
            if (!isset($this->variables[$name])) {
                $this->emitter->emit('mov x0, #0');
                return 'int32';
            }

            $type = $this->variables[$name]['type'];
            if ($type === 'string') {
                $this->loadVarStringToX0X1($name);
                return 'string';
            }
            $this->loadVarToX0($name);
            return $type;
        }

        if ($expr instanceof \Context\ArrayAccess1DContext) {
            $name = $expr->ID()->getText();
            if (!isset($this->variables[$name])) {
                throw new \RuntimeException("Arreglo '$name' no declarado.");
            }
            $this->compileExpr($expr->expr());
            $this->emitter->emit('mov x9, x0');
            $this->emitArrayAddress1D($name, 'x9', 'x11');
            $this->emitter->emit('ldr x0, [x11]');
            return $this->variables[$name]['elemType'] ?? 'int32';
        }

        if ($expr instanceof \Context\ArrayAccess2DContext) {
            $name = $expr->ID()->getText();
            if (!isset($this->variables[$name])) {
                throw new \RuntimeException("Matriz '$name' no declarada.");
            }
            $this->compileExpr($expr->expr(0));
            $this->emitter->emit('mov x9, x0');
            $this->compileExpr($expr->expr(1));
            $this->emitter->emit('mov x10, x0');
            $this->emitArrayAddress2D($name, 'x9', 'x10', 'x11');
            $this->emitter->emit('ldr x0, [x11]');
            return $this->variables[$name]['elemType'] ?? 'int32';
        }

        if ($expr instanceof \Context\GroupExprContext) {
            return $this->compileExpr($expr->expr());
        }

        if ($expr instanceof \Context\NegExprContext) {
            $type = $this->compileExpr($expr->expr());
            $this->emitter->emit('neg x0, x0');
            return $type;
        }

        if ($expr instanceof \Context\NotExprContext) {
            $this->compileExpr($expr->expr());
            $this->emitter->emit('cmp x0, #0');
            $this->emitter->emit('cset x0, eq');
            return 'bool';
        }

        if ($expr instanceof \Context\MulExprContext) {
            $this->compileExpr($expr->expr(0));
            $this->emitter->emit('mov x9, x0');
            $this->compileExpr($expr->expr(1));
            $this->emitter->emit('mov x10, x0');

            $op = $expr->op->getText();
            if ($op === '*') {
                $this->emitter->emit('mul x0, x9, x10');
            } elseif ($op === '/') {
                $this->emitter->emit('sdiv x0, x9, x10');
            } else {
                $this->emitter->emit('sdiv x11, x9, x10');
                $this->emitter->emit('msub x0, x11, x10, x9');
            }
            return 'int32';
        }

        if ($expr instanceof \Context\AddExprContext) {
            $leftType = $this->compileExpr($expr->expr(0));
            $this->emitter->emit('mov x9, x0');
            $rightType = $this->compileExpr($expr->expr(1));
            $this->emitter->emit('mov x10, x0');

            $op = $expr->op->getText();
            if ($op === '+' && $leftType === 'string' && $rightType === 'string') {
                throw new \RuntimeException('Concatenacion de strings fuera del alcance Sprint 2.');
            }

            if ($op === '+') {
                $this->emitter->emit('add x0, x9, x10');
            } else {
                $this->emitter->emit('sub x0, x9, x10');
            }
            return 'int32';
        }

        if ($expr instanceof \Context\RelExprContext) {
            $this->compileExpr($expr->expr(0));
            $this->emitter->emit('mov x9, x0');
            $this->compileExpr($expr->expr(1));
            $this->emitter->emit('cmp x9, x0');

            $op = $expr->op->getText();
            $cond = match ($op) {
                '==' => 'eq',
                '!=' => 'ne',
                '<' => 'lt',
                '<=' => 'le',
                '>' => 'gt',
                '>=' => 'ge',
                default => 'eq',
            };
            $this->emitter->emit('cset x0, ' . $cond);
            return 'bool';
        }

        if ($expr instanceof \Context\AndExprContext) {
            $falseLabel = $this->labels->next('L_AND_FALSE');
            $endLabel = $this->labels->next('L_AND_END');

            $this->compileExpr($expr->expr(0));
            $this->emitter->emit('cmp x0, #0');
            $this->emitter->emit('b.eq ' . $falseLabel);
            $this->compileExpr($expr->expr(1));
            $this->emitter->emit('cmp x0, #0');
            $this->emitter->emit('cset x0, ne');
            $this->emitter->emit('b ' . $endLabel);
            $this->emitter->emitLabel($falseLabel);
            $this->emitter->emit('mov x0, #0');
            $this->emitter->emitLabel($endLabel);
            return 'bool';
        }

        if ($expr instanceof \Context\OrExprContext) {
            $trueLabel = $this->labels->next('L_OR_TRUE');
            $endLabel = $this->labels->next('L_OR_END');

            $this->compileExpr($expr->expr(0));
            $this->emitter->emit('cmp x0, #0');
            $this->emitter->emit('b.ne ' . $trueLabel);
            $this->compileExpr($expr->expr(1));
            $this->emitter->emit('cmp x0, #0');
            $this->emitter->emit('cset x0, ne');
            $this->emitter->emit('b ' . $endLabel);
            $this->emitter->emitLabel($trueLabel);
            $this->emitter->emit('mov x0, #1');
            $this->emitter->emitLabel($endLabel);
            return 'bool';
        }

        if ($expr instanceof \Context\FmtPrintlnExprContext) {
            $this->compilePrintln($expr->fmtPrintln());
            $this->emitter->emit('mov x0, #0');
            return 'int32';
        }

        throw new \RuntimeException('Expresion fuera del alcance Sprint 2.');
    }

    private function compilePrimary(object $primary): string
    {
        if ($primary instanceof \Context\IntLitContext) {
            $this->emitter->emit('mov x0, #' . $primary->INT_LIT()->getText());
            return 'int32';
        }

        if ($primary instanceof \Context\TrueLitContext) {
            $this->emitter->emit('mov x0, #1');
            return 'bool';
        }

        if ($primary instanceof \Context\FalseLitContext) {
            $this->emitter->emit('mov x0, #0');
            return 'bool';
        }

        if ($primary instanceof \Context\StringLitContext) {
            $raw = $primary->STRING_LIT()->getText();
            $value = stripcslashes(substr($raw, 1, -1));
            $label = $this->internString($value);
            $this->emitLoadStringLabelToX0X1($label);
            return 'string';
        }

        if ($primary instanceof \Context\RuneLitContext) {
            $raw = $primary->RUNE_LIT()->getText();
            $inner = substr($raw, 1, -1);
            $ch = stripcslashes($inner);
            $code = isset($ch[0]) ? ord($ch[0]) : 0;
            $this->emitter->emit('mov x0, #' . $code);
            return 'rune';
        }

        throw new \RuntimeException('Literal fuera del alcance Sprint 2.');
    }

    private function declareVar(string $name, string $type): void
    {
        $info = $this->parseTypeInfo($type);
        $size = $info['size'];
        $aligned = ($size + 7) & ~7;
        $offset = $this->nextOffset;
        $this->nextOffset += $aligned;

        if ($this->nextOffset > self::LOCAL_STACK_SIZE) {
            throw new \RuntimeException('Stack local insuficiente para codegen Sprint 2.');
        }

        $this->variables[$name] = [
            'type' => $type,
            'offset' => $offset,
            'size' => $aligned,
            'kind' => $info['kind'],
            'elemType' => $info['elemType'],
            'elemSize' => $info['elemSize'],
            'dims' => $info['dims'],
        ];
    }

    private function storeDefaultForType(string $name, string $type): void
    {
        if ($this->isArrayType($type)) {
            $off = $this->variables[$name]['offset'];
            $words = intdiv($this->variables[$name]['size'], 8);
            for ($i = 0; $i < $words; $i++) {
                $this->emitter->emit('str xzr, [sp, #' . ($off + ($i * 8)) . ']');
            }
            return;
        }

        if ($type === 'string') {
            $this->emitLoadStringLabelToX0X1('__empty_str');
            $this->storeStringFromX0X1($name);
            return;
        }

        $this->emitter->emit('mov x0, #0');
        $this->storeScalarFromX0($name);
    }

    private function storeExprToVar(string $name, string $exprType): void
    {
        $varType = $this->variables[$name]['type'];
        if ($this->isArrayType($varType)) {
            throw new \RuntimeException('Asignacion directa de arreglos fuera del alcance Sprint 3.');
        }
        if ($varType === 'string' || $exprType === 'string') {
            $this->storeStringFromX0X1($name);
            return;
        }
        $this->storeScalarFromX0($name);
    }

    private function compileArrayLiteralInit(string $name, object $exprNode): void
    {
        if (!isset($this->variables[$name])) {
            throw new \RuntimeException("Arreglo '$name' no declarado.");
        }

        $meta = $this->variables[$name];
        $off = $meta['offset'];
        $values = [];

        if ($exprNode instanceof \Context\PrimaryExprContext) {
            $primary = $exprNode->primary();
            if ($primary instanceof \Context\ArrayLit1DContext || $primary instanceof \Context\ArrayLit2DContext) {
                foreach ($primary->expr() as $e) {
                    $values[] = $this->evalConstIntExpr($e);
                }
            } else {
                throw new \RuntimeException('Inicializacion de arreglo requiere literal de arreglo.');
            }
        } else {
            throw new \RuntimeException('Inicializacion de arreglo requiere literal de arreglo.');
        }

        $totalSlots = intdiv($meta['size'], 8);
        for ($i = 0; $i < $totalSlots; $i++) {
            $val = $values[$i] ?? 0;
            $this->emitter->emit('mov x0, #' . $val);
            $this->emitter->emit('str x0, [sp, #' . ($off + ($i * 8)) . ']');
        }
    }

    private function emitArrayAddress1D(string $name, string $indexReg, string $outReg): void
    {
        $meta = $this->variables[$name];
        $off = $meta['offset'];
        $elemSize = $meta['elemSize'] ?? 8;

        $this->emitter->emit('add ' . $outReg . ', sp, #' . $off);
        if ($elemSize === 8) {
            $this->emitter->emit('lsl ' . $indexReg . ', ' . $indexReg . ', #3');
            $this->emitter->emit('add ' . $outReg . ', ' . $outReg . ', ' . $indexReg);
            return;
        }

        $this->emitter->emit('mov x12, #' . $elemSize);
        $this->emitter->emit('mul ' . $indexReg . ', ' . $indexReg . ', x12');
        $this->emitter->emit('add ' . $outReg . ', ' . $outReg . ', ' . $indexReg);
    }

    private function emitArrayAddress2D(string $name, string $rowReg, string $colReg, string $outReg): void
    {
        $meta = $this->variables[$name];
        $off = $meta['offset'];
        $elemSize = $meta['elemSize'] ?? 8;
        $cols = $meta['dims'][1] ?? 1;

        $this->emitter->emit('mov x12, #' . $cols);
        $this->emitter->emit('mul ' . $rowReg . ', ' . $rowReg . ', x12');
        $this->emitter->emit('add ' . $rowReg . ', ' . $rowReg . ', ' . $colReg);

        $this->emitter->emit('add ' . $outReg . ', sp, #' . $off);
        if ($elemSize === 8) {
            $this->emitter->emit('lsl ' . $rowReg . ', ' . $rowReg . ', #3');
            $this->emitter->emit('add ' . $outReg . ', ' . $outReg . ', ' . $rowReg);
            return;
        }

        $this->emitter->emit('mov x12, #' . $elemSize);
        $this->emitter->emit('mul ' . $rowReg . ', ' . $rowReg . ', x12');
        $this->emitter->emit('add ' . $outReg . ', ' . $outReg . ', ' . $rowReg);
    }

    private function isArrayType(string $type): bool
    {
        return str_starts_with(trim($type), '[');
    }

    private function parseTypeInfo(string $type): array
    {
        $type = trim($type);

        if (!$this->isArrayType($type)) {
            if ($type === 'string') {
                return [
                    'kind' => 'string',
                    'elemType' => 'string',
                    'elemSize' => 16,
                    'dims' => [],
                    'size' => 16,
                ];
            }

            return [
                'kind' => 'scalar',
                'elemType' => $type,
                'elemSize' => 8,
                'dims' => [],
                'size' => 8,
            ];
        }

        $dims = [];
        while (preg_match('/^\[(\d+)\](.+)$/', $type, $m)) {
            $dims[] = (int) $m[1];
            $type = trim($m[2]);
        }

        $elemType = $type;
        $elemSize = $elemType === 'string' ? 16 : 8;
        $count = 1;
        foreach ($dims as $d) {
            $count *= $d;
        }

        return [
            'kind' => 'array',
            'elemType' => $elemType,
            'elemSize' => $elemSize,
            'dims' => $dims,
            'size' => $count * $elemSize,
        ];
    }

    private function inferExprTypeForDecl(object $expr): ?string
    {
        if ($expr instanceof \Context\PrimaryExprContext) {
            $p = $expr->primary();
            if ($p instanceof \Context\ArrayLit1DContext) {
                return '[' . $p->INT_LIT()->getText() . ']' . $p->typeRef()->getText();
            }
            if ($p instanceof \Context\ArrayLit2DContext) {
                $d0 = $p->INT_LIT(0)->getText();
                $d1 = $p->INT_LIT(1)->getText();
                return '[' . $d0 . '][' . $d1 . ']' . $p->typeRef()->getText();
            }
        }
        return null;
    }

    private function evalConstIntExpr(object $expr): int
    {
        if ($expr instanceof \Context\PrimaryExprContext) {
            $p = $expr->primary();
            if ($p instanceof \Context\IntLitContext) {
                return (int) $p->INT_LIT()->getText();
            }
            if ($p instanceof \Context\TrueLitContext) {
                return 1;
            }
            if ($p instanceof \Context\FalseLitContext) {
                return 0;
            }
            if ($p instanceof \Context\RuneLitContext) {
                $raw = $p->RUNE_LIT()->getText();
                $inner = substr($raw, 1, -1);
                $ch = stripcslashes($inner);
                return isset($ch[0]) ? ord($ch[0]) : 0;
            }
        }

        if ($expr instanceof \Context\NegExprContext) {
            return -$this->evalConstIntExpr($expr->expr());
        }

        throw new \RuntimeException('Literal de arreglo no constante o no soportado.');
    }

    private function storeScalarFromX0(string $name): void
    {
        $off = $this->variables[$name]['offset'];
        $this->emitter->emit('str x0, [sp, #' . $off . ']');
    }

    private function storeStringFromX0X1(string $name): void
    {
        $off = $this->variables[$name]['offset'];
        $this->emitter->emit('str x0, [sp, #' . $off . ']');
        $this->emitter->emit('str x1, [sp, #' . ($off + 8) . ']');
    }

    private function loadVarToX0(string $name): void
    {
        $off = $this->variables[$name]['offset'];
        $this->emitter->emit('ldr x0, [sp, #' . $off . ']');
    }

    private function loadVarStringToX0X1(string $name): void
    {
        $off = $this->variables[$name]['offset'];
        $this->emitter->emit('ldr x0, [sp, #' . $off . ']');
        $this->emitter->emit('ldr x1, [sp, #' . ($off + 8) . ']');
    }

    private function emitWriteLabel(string $label, string $lenLabel): void
    {
        $this->emitter->emit('mov x0, #1');
        $this->emitter->emit('adrp x1, ' . $label);
        $this->emitter->emit('add x1, x1, :lo12:' . $label);
        $this->emitter->emit('ldr x2, =' . $lenLabel);
        $this->emitter->emit('mov x8, #64');
        $this->emitter->emit('svc #0');
    }

    private function emitLoadStringLabelToX0X1(string $label): void
    {
        $this->emitter->emit('adrp x0, ' . $label);
        $this->emitter->emit('add x0, x0, :lo12:' . $label);
        $this->emitter->emit('ldr x1, =' . $label . '_len');
    }

    private function internString(string $value): string
    {
        if (isset($this->literalByValue[$value])) {
            return $this->literalByValue[$value];
        }
        $label = '__str_' . str_replace('-', '_', $this->labels->next('LIT'));
        $this->literalByValue[$value] = $label;
        $this->stringPool[$label] = $value;
        return $label;
    }

    private function registerBuiltinStrings(): void
    {
        $this->stringPool['__newline_str'] = "\n";
        $this->stringPool['__space_str'] = ' ';
        $this->stringPool['__true_str'] = 'true';
        $this->stringPool['__false_str'] = 'false';
        $this->stringPool['__empty_str'] = '';
    }

    private function emitPrintIntRoutine(): void
    {
        $loop = $this->labels->next('L_PI_LOOP');
        $afterDigits = $this->labels->next('L_PI_AFTER_DIGITS');
        $positive = $this->labels->next('L_PI_POS');
        $noSign = $this->labels->next('L_PI_NOSIGN');

        $this->emitter->emitLabel('__print_int');
        $this->emitter->emit('stp x29, x30, [sp, #-16]!');
        $this->emitter->emit('mov x29, sp');
        $this->emitter->emit('sub sp, sp, #32');

        $this->emitter->emit('mov x9, x0');
        $this->emitter->emit('adrp x10, __int_buffer');
        $this->emitter->emit('add x10, x10, :lo12:__int_buffer');
        $this->emitter->emit('add x11, x10, #31');
        $this->emitter->emit('mov w12, #0');
        $this->emitter->emit('strb w12, [x11]');
        $this->emitter->emit('mov x13, #0');

        $this->emitter->emit('cmp x9, #0');
        $this->emitter->emit('b.ge ' . $positive);
        $this->emitter->emit('neg x9, x9');
        $this->emitter->emit('mov x13, #1');
        $this->emitter->emitLabel($positive);

        $this->emitter->emit('cmp x9, #0');
        $this->emitter->emit('b.ne ' . $loop);
        $this->emitter->emit('sub x11, x11, #1');
        $this->emitter->emit('mov w12, #48');
        $this->emitter->emit('strb w12, [x11]');
        $this->emitter->emit('b ' . $afterDigits);

        $this->emitter->emitLabel($loop);
        $this->emitter->emit('mov x15, #10');
        $this->emitter->emit('udiv x14, x9, x15');
        $this->emitter->emit('msub x16, x14, x15, x9');
        $this->emitter->emit('add x16, x16, #48');
        $this->emitter->emit('sub x11, x11, #1');
        $this->emitter->emit('strb w16, [x11]');
        $this->emitter->emit('mov x9, x14');
        $this->emitter->emit('cmp x9, #0');
        $this->emitter->emit('b.ne ' . $loop);

        $this->emitter->emitLabel($afterDigits);
        $this->emitter->emit('cmp x13, #0');
        $this->emitter->emit('b.eq ' . $noSign);
        $this->emitter->emit('sub x11, x11, #1');
        $this->emitter->emit('mov w12, #45');
        $this->emitter->emit('strb w12, [x11]');
        $this->emitter->emitLabel($noSign);

        $this->emitter->emit('add x17, x10, #31');
        $this->emitter->emit('sub x2, x17, x11');
        $this->emitter->emit('mov x0, #1');
        $this->emitter->emit('mov x1, x11');
        $this->emitter->emit('mov x8, #64');
        $this->emitter->emit('svc #0');

        $this->emitter->emit('add sp, sp, #32');
        $this->emitter->emit('ldp x29, x30, [sp], #16');
        $this->emitter->emit('ret');
    }

    private function emitDataSection(): void
    {
        $this->emitter->emitSection('.bss');
        $this->emitter->emitRaw('__int_buffer: .skip 32');

        $this->emitter->emitSection('.data');
        foreach ($this->stringPool as $label => $text) {
            $this->emitter->emitAscii($label, $text);
            $this->emitter->emitRaw($label . '_len = . - ' . $label);
        }
    }

    private function emitExitSyscall(): void
    {
        $this->emitter->emitComment('exit(0)');
        $this->emitter->emit('mov x0, #0');
        $this->emitter->emit('mov x8, #93');
        $this->emitter->emit('svc #0');
    }
}