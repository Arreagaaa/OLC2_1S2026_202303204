<?php

namespace App\Interpreter;

use App\Models\Symbol;
use App\Models\ErrorEntry;

// clases de contexto generadas por ANTLR4
use Context\ProgramRuleContext;
use Context\TopFuncDeclContext;
use Context\TopVarDeclContext;
use Context\TopConstDeclContext;
use Context\BlockStmtContext;
use Context\VarDeclStmtContext;
use Context\ConstDeclStmtContext;
use Context\ShortDeclStmtContext;
use Context\BlockOnlyStmtContext;
use Context\AssignStmtContext;
use Context\CompoundAssignStmtContext;
use Context\ReturnStmtContext;
use Context\BreakStmtContext;
use Context\ContinueStmtContext;
use Context\IfStmtWrapContext;
use Context\SwitchStmtWrapContext;
use Context\ForStmtWrapContext;
use Context\CallStmtContext;
use Context\PrintlnStmtContext;
use Context\ArrayAssignStmtContext;
use Context\IncStmtContext;
use Context\DecStmtContext;

use Context\FunctionDeclarationContext;
use Context\IfStmtRuleContext;
use Context\SwitchStmtRuleContext;
use Context\CaseClauseRuleContext;
use Context\DefaultClauseContext;
use Context\ForInfiniteContext;
use Context\ForWhileContext;
use Context\ForClassicContext;
use Context\ForInitShortContext;
use Context\ForInitAssignContext;
use Context\ForPostAssignContext;
use Context\ForPostCompoundContext;
use Context\ForPostIncContext;
use Context\ForPostDecContext;
use Context\VarDeclSimpleContext;
use Context\VarDeclInferContext;
use Context\ConstDeclRuleContext;
use Context\ShortDeclRuleContext;
use Context\SimpleAssignContext;
use Context\DerefAssignContext;
use Context\CompoundAssignRuleContext;
use Context\ArrayAssign1DContext;
use Context\ArrayAssign2DContext;
use Context\ArrayAssign3DContext;

use Context\PrimaryExprContext;
use Context\IdExprContext;
use Context\CallExprWrapContext;
use Context\FmtPrintlnExprContext;
use Context\ArrayAccess1DContext;
use Context\ArrayAccess2DContext;
use Context\ArrayAccess3DContext;
use Context\RefExprContext;
use Context\DerefExprContext;
use Context\CastExprContext;
use Context\GroupExprContext;
use Context\NotExprContext;
use Context\NegExprContext;
use Context\MulExprContext;
use Context\AddExprContext;
use Context\RelExprContext;
use Context\AndExprContext;
use Context\OrExprContext;

use Context\IntLitContext;
use Context\FloatLitContext;
use Context\StringLitContext;
use Context\RuneLitContext;
use Context\TrueLitContext;
use Context\FalseLitContext;
use Context\NilLitContext;
use Context\ArrayLit1DContext;
use Context\ArrayLit2DContext;
use Context\ArrayLit3DContext;

use Context\UserFuncCallContext;
use Context\FmtPrintlnCallContext;
use Context\ParamDeclContext;
use Context\ParamListContext;
use Context\ArgListContext;
use Context\BuiltinStmtContext;
use Context\BuiltinExprContext;
use Context\BuiltinLenContext;
use Context\BuiltinNowContext;
use Context\BuiltinSubstrContext;
use Context\BuiltinTypeOfContext;

use Context\FunctionDeclarationContext as FuncDeclCtx;

// visitor principal: recorre el CST, evalua expresiones y ejecuta sentencias
class Interpreter extends \GolampiBaseVisitor
{
    public string      $console = '';
    public Environment $env;
    private Environment $globalEnv;
    public ErrorHandler $errors;
    // tabla de simbolos acumulada para el reporte
    private array $symbolTable = [];

    public function __construct()
    {
        $this->errors    = new ErrorHandler();
        $this->globalEnv = new Environment();
        $this->env       = $this->globalEnv;
    }

    // ─── programa ──────────────────────────────────────────────────────────────

    // primer recorrido: registra todas las funciones (hoisting)
    // segundo recorrido: ejecuta declaraciones de nivel superior
    // luego llama a main()
    public function visitProgramRule(ProgramRuleContext $ctx): mixed
    {
        // hoisting de funciones
        foreach ($ctx->topDecl() as $decl) {
            if ($decl instanceof TopFuncDeclContext) {
                $this->visit($decl);
            }
        }

        // ejecutar declaraciones globales que no sean funciones
        foreach ($ctx->topDecl() as $decl) {
            if (!($decl instanceof TopFuncDeclContext)) {
                $this->visit($decl);
            }
        }

        // llamar a main
        try {
            $mainSym = $this->globalEnv->get('main');
            if ($mainSym->valor instanceof FuncionUsuario) {
                $mainSym->valor->invoke($this, []);
            }
        } catch (\RuntimeException $e) {
            $this->errors->addSemantic('No se encontro la funcion main().', 0, 0);
        }

        return $this->console;
    }

    // ─── declaraciones de nivel superior ──────────────────────────────────────

    public function visitTopFuncDecl(TopFuncDeclContext $ctx): mixed
    {
        return $this->visit($ctx->funcDecl());
    }

    public function visitTopVarDecl(TopVarDeclContext $ctx): mixed
    {
        return $this->visit($ctx->varDecl());
    }

    public function visitTopConstDecl(TopConstDeclContext $ctx): mixed
    {
        return $this->visit($ctx->constDecl());
    }

    // ─── declaracion de funcion ────────────────────────────────────────────────

    public function visitFunctionDeclaration(FunctionDeclarationContext $ctx): mixed
    {
        $name   = $ctx->ID()->getText();
        $line   = $ctx->ID()->getSymbol()->getLine();
        $col    = $ctx->ID()->getSymbol()->getCharPositionInLine();
        $params = [];

        if ($ctx->paramList() !== null) {
            $params = $this->visit($ctx->paramList());
        }

        $returnTypes = [];
        if ($ctx->returnType() !== null) {
            $rt = $ctx->returnType();
            // MultiReturn: (type1, type2, ...)
            if ($rt instanceof \Context\MultiReturnContext) {
                foreach ($rt->typeList()->typeRef() as $t) {
                    $returnTypes[] = $t->getText();
                }
            } else {
                // SingleReturn: solo un tipo
                $returnTypes[] = $rt->getText();
            }
        }

        // validar restricciones de main: sin parametros, sin retorno, unica
        if ($name === 'main') {
            if (count($params) > 0) {
                $this->errors->addSemantic('La funcion main() no puede tener parametros.', $line, $col);
                return null;
            }
            if (count($returnTypes) > 0) {
                $this->errors->addSemantic('La funcion main() no puede retornar valores.', $line, $col);
                return null;
            }
            // verificar que no haya otra main ya registrada
            if ($this->globalEnv->getLocal('main') !== null) {
                $this->errors->addSemantic('La funcion main() ya fue declarada. Solo puede existir una.', $line, $col);
                return null;
            }
        }

        $funcion = new FuncionUsuario($ctx, $this->env, $params, $returnTypes);

        $sym = new Symbol(
            $name,
            'funcion',
            $funcion,
            Symbol::CLASE_FUNCION,
            'global',
            $line,
            $col
        );

        $this->env->declare($name, $sym);
        $this->symbolTable[] = $sym;

        return null;
    }

    public function visitParamList(ParamListContext $ctx): array
    {
        $params = [];
        foreach ($ctx->param() as $p) {
            $params[] = $this->visit($p);
        }
        return $params;
    }

    public function visitParamDecl(ParamDeclContext $ctx): array
    {
        $type  = $ctx->typeRef()->getText();
        $byRef = str_starts_with($type, '*');
        $name  = $ctx->ID()->getText();
        return ['name' => $name, 'type' => $type, 'byRef' => $byRef];
    }

    // ─── bloque ────────────────────────────────────────────────────────────────

    public function visitBlockStmt(BlockStmtContext $ctx): mixed
    {
        $prevEnv  = $this->env;
        $this->env = new Environment($prevEnv);

        $result = null;
        foreach ($ctx->stmt() as $stmt) {
            $result = $this->visit($stmt);
            if ($result instanceof FlowType) {
                $this->env = $prevEnv;
                return $result;
            }
        }

        $this->env = $prevEnv;
        return null;
    }

    // ─── sentencias wrapper ───────────────────────────────────────────────────

    public function visitVarDeclStmt(VarDeclStmtContext $ctx): mixed
    {
        return $this->visit($ctx->varDecl());
    }

    public function visitConstDeclStmt(ConstDeclStmtContext $ctx): mixed
    {
        return $this->visit($ctx->constDecl());
    }

    public function visitShortDeclStmt(ShortDeclStmtContext $ctx): mixed
    {
        return $this->visit($ctx->shortDecl());
    }

    public function visitAssignStmt(AssignStmtContext $ctx): mixed
    {
        return $this->visit($ctx->assignment());
    }

    public function visitCompoundAssignStmt(CompoundAssignStmtContext $ctx): mixed
    {
        return $this->visit($ctx->compoundAssign());
    }

    public function visitIfStmtWrap(IfStmtWrapContext $ctx): mixed
    {
        return $this->visit($ctx->ifStmt());
    }

    public function visitSwitchStmtWrap(SwitchStmtWrapContext $ctx): mixed
    {
        return $this->visit($ctx->switchStmt());
    }

    public function visitForStmtWrap(ForStmtWrapContext $ctx): mixed
    {
        return $this->visit($ctx->forStmt());
    }

    public function visitCallStmt(CallStmtContext $ctx): mixed
    {
        return $this->visit($ctx->callExpr());
    }

    public function visitArrayAssignStmt(ArrayAssignStmtContext $ctx): mixed
    {
        return $this->visit($ctx->arrayAssign());
    }

    public function visitBlockOnlyStmt(BlockOnlyStmtContext $ctx): mixed
    {
        return $this->visit($ctx->block());
    }

    public function visitIncStmt(IncStmtContext $ctx): mixed
    {
        $name = $ctx->ID()->getText();
        try {
            $sym = $this->env->get($name);
            $this->env->assign($name, $sym->valor + 1);
        } catch (\RuntimeException $e) {
            $this->errors->addSemantic($e->getMessage(),
                $ctx->ID()->getSymbol()->getLine(),
                $ctx->ID()->getSymbol()->getCharPositionInLine());
        }
        return null;
    }

    public function visitDecStmt(DecStmtContext $ctx): mixed
    {
        $name = $ctx->ID()->getText();
        try {
            $sym = $this->env->get($name);
            $this->env->assign($name, $sym->valor - 1);
        } catch (\RuntimeException $e) {
            $this->errors->addSemantic($e->getMessage(),
                $ctx->ID()->getSymbol()->getLine(),
                $ctx->ID()->getSymbol()->getCharPositionInLine());
        }
        return null;
    }

    // ─── fmt.Println ──────────────────────────────────────────────────────────

    public function visitPrintlnStmt(PrintlnStmtContext $ctx): mixed
    {
        return $this->visit($ctx->fmtPrintln());
    }

    public function visitFmtPrintlnCall(FmtPrintlnCallContext $ctx): mixed
    {
        $parts = [];
        if ($ctx->argList() !== null) {
            foreach ($ctx->argList()->expr() as $e) {
                $val = $this->visit($e);
                $parts[] = $this->valueToString($val);
            }
        }
        $this->console .= implode(' ', $parts) . "\n";
        return null;
    }

    // fmt.Println dentro de una expresion (sin sentido real pero la gramatica lo permite)
    public function visitFmtPrintlnExpr(FmtPrintlnExprContext $ctx): mixed
    {
        return $this->visit($ctx->fmtPrintln());
    }

    // ─── declaraciones de variables ───────────────────────────────────────────

    public function visitVarDeclSimple(VarDeclSimpleContext $ctx): mixed
    {
        $type  = $ctx->typeRef()->getText();
        $ids   = $ctx->idList()->ID();
        $exprs = ($ctx->exprList() !== null) ? $ctx->exprList()->expr() : [];

        foreach ($ids as $i => $idNode) {
            $name = $idNode->getText();
            $line = $idNode->getSymbol()->getLine();
            $col  = $idNode->getSymbol()->getCharPositionInLine();

            if ($this->env->existsLocal($name)) {
                $this->errors->addSemantic("Variable '$name' ya declarada en este scope.", $line, $col);
                continue;
            }

            $value = isset($exprs[$i])
                ? $this->visit($exprs[$i])
                : $this->defaultValue($type);

            // validar compatibilidad de tipos si se proporciono expresion
            if (isset($exprs[$i]) && !$this->isTypeCompatible($type, $value)) {
                $valueType = $this->inferType($value);
                $this->errors->addSemantic(
                    "Incompatibilidad de tipos: se esperaba '$type' pero se obtuvo '$valueType'.",
                    $line, $col
                );
                continue;
            }

            $sym = new Symbol($name, $type, $value, Symbol::CLASE_VARIABLE, 'local', $line, $col);
            $this->env->declare($name, $sym);
            $this->symbolTable[] = $sym;
        }

        return null;
    }

    public function visitVarDeclInfer(VarDeclInferContext $ctx): mixed
    {
        $ids   = $ctx->idList()->ID();
        $exprs = $ctx->exprList()->expr();

        // Caso especial: var a, b, c = f() con retorno múltiple.
        if (count($ids) > 1 && count($exprs) === 1) {
            $result = $this->visit($exprs[0]);
            if (is_array($result) && isset($result['__multi__'])) {
                $values = $result['__multi__'];
                foreach ($ids as $i => $idNode) {
                    $name  = $idNode->getText();
                    $line  = $idNode->getSymbol()->getLine();
                    $col   = $idNode->getSymbol()->getCharPositionInLine();
                    $value = $values[$i] ?? null;
                    $type  = $this->inferType($value);

                    if ($this->env->existsLocal($name)) {
                        $this->errors->addSemantic("Variable '$name' ya declarada en este scope.", $line, $col);
                        continue;
                    }

                    $sym = new Symbol($name, $type, $value, Symbol::CLASE_VARIABLE, 'local', $line, $col);
                    $this->env->declare($name, $sym);
                    $this->symbolTable[] = $sym;
                }
                return null;
            }
        }

        foreach ($ids as $i => $idNode) {
            $name     = $idNode->getText();
            $exprNode = $exprs[$i] ?? null;
            $value    = $exprNode !== null ? $this->visit($exprNode) : null;
            $type     = $this->typeFromExprAst($exprNode) ?? $this->inferType($value);
            $line     = $idNode->getSymbol()->getLine();
            $col      = $idNode->getSymbol()->getCharPositionInLine();

            if ($this->env->existsLocal($name)) {
                $this->errors->addSemantic("Variable '$name' ya declarada en este scope.", $line, $col);
                continue;
            }

            $sym = new Symbol($name, $type, $value, Symbol::CLASE_VARIABLE, 'local', $line, $col);
            $this->env->declare($name, $sym);
            $this->symbolTable[] = $sym;
        }

        return null;
    }

    public function visitConstDeclRule(ConstDeclRuleContext $ctx): mixed
    {
        $type  = $ctx->typeRef()->getText();
        $name  = $ctx->ID()->getText();
        $value = $this->visit($ctx->expr());
        $line  = $ctx->ID()->getSymbol()->getLine();
        $col   = $ctx->ID()->getSymbol()->getCharPositionInLine();

        if ($this->env->existsLocal($name)) {
            $this->errors->addSemantic("Constante '$name' ya declarada en este scope.", $line, $col);
            return null;
        }

        // validar compatibilidad de tipos
        if (!$this->isTypeCompatible($type, $value)) {
            $valueType = $this->inferType($value);
            $this->errors->addSemantic(
                "Incompatibilidad de tipos: se esperaba '$type' pero se obtuvo '$valueType'.",
                $line, $col
            );
            return null;
        }

        $sym = new Symbol($name, $type, $value, Symbol::CLASE_CONSTANTE, 'local', $line, $col);
        $this->env->declare($name, $sym);
        $this->symbolTable[] = $sym;

        return null;
    }

    public function visitShortDeclRule(ShortDeclRuleContext $ctx): mixed
    {
        $ids   = $ctx->idList()->ID();
        $exprs = $ctx->exprList()->expr();

        // Caso especial: N ids := 1 llamada que retorna N valores (múltiple retorno)
        if (count($ids) > 1 && count($exprs) === 1) {
            $result = $this->visit($exprs[0]);
            // si la función retornó un array marcado como multi-return
            if (is_array($result) && isset($result['__multi__'])) {
                $values = $result['__multi__'];
                $hasNewVar = false; // validar que al menos una es nueva
                foreach ($ids as $i => $idNode) {
                    $name  = $idNode->getText();
                    $value = $values[$i] ?? null;
                    $type  = $this->inferType($value);
                    $line  = $idNode->getSymbol()->getLine();
                    $col   = $idNode->getSymbol()->getCharPositionInLine();
                    if ($this->env->existsLocal($name)) {
                        $this->env->assign($name, $value);
                        continue;
                    }
                    $hasNewVar = true;
                    $sym = new Symbol($name, $type, $value, Symbol::CLASE_VARIABLE, 'local', $line, $col);
                    $this->env->declare($name, $sym);
                    $this->symbolTable[] = $sym;
                }
                if (!$hasNewVar) {
                    $this->errors->addSemantic(
                        'La declaracion corta := debe declarar al menos una variable nueva.',
                        $ids[0]->getSymbol()->getLine(),
                        $ids[0]->getSymbol()->getCharPositionInLine()
                    );
                }
                return null;
            }
        }

        // validar que al menos una variable sea nueva
        $hasNewVar = false;
        foreach ($ids as $idNode) {
            if (!$this->env->existsLocal($idNode->getText())) {
                $hasNewVar = true;
                break;
            }
        }
        if (!$hasNewVar) {
            $this->errors->addSemantic(
                'La declaracion corta := debe declarar al menos una variable nueva.',
                $ids[0]->getSymbol()->getLine(),
                $ids[0]->getSymbol()->getCharPositionInLine()
            );
            return null;
        }

        foreach ($ids as $i => $idNode) {
            $name     = $idNode->getText();
            $exprNode = $exprs[$i] ?? null;
            $value    = $exprNode !== null ? $this->visit($exprNode) : null;
            $type     = $this->typeFromExprAst($exprNode) ?? $this->inferType($value);
            $line     = $idNode->getSymbol()->getLine();
            $col      = $idNode->getSymbol()->getCharPositionInLine();

            if ($this->env->existsLocal($name)) {
                // re-asignacion permitida en short decl si ya existe en el mismo scope
                $this->env->assign($name, $value);
                continue;
            }

            $sym = new Symbol($name, $type, $value, Symbol::CLASE_VARIABLE, 'local', $line, $col);
            $this->env->declare($name, $sym);
            $this->symbolTable[] = $sym;
        }

        return null;
    }

    // ─── asignaciones ─────────────────────────────────────────────────────────

    public function visitSimpleAssign(SimpleAssignContext $ctx): mixed
    {
        $name  = $ctx->ID()->getText();
        $value = $this->visit($ctx->expr());
        $line  = $ctx->ID()->getSymbol()->getLine();
        $col   = $ctx->ID()->getSymbol()->getCharPositionInLine();

        try {
            $sym = $this->env->get($name);
            if ($sym->clase === Symbol::CLASE_CONSTANTE) {
                $this->errors->addSemantic("No se puede reasignar la constante '$name'.", $line, $col);
                return null;
            }
            $this->env->assign($name, $value);
        } catch (\RuntimeException $e) {
            $this->errors->addSemantic($e->getMessage(), $line, $col);
        }

        return null;
    }

    public function visitDerefAssign(DerefAssignContext $ctx): mixed
    {
        $name  = $ctx->ID()->getText();
        $value = $this->visit($ctx->expr());
        $line  = $ctx->ID()->getSymbol()->getLine();
        $col   = $ctx->ID()->getSymbol()->getCharPositionInLine();

        try {
            $sym = $this->env->get($name);

            // Caso 1: parámetro byRef (refName/refEnv apunta al símbolo original).
            if ($sym->refName !== null && $sym->refEnv !== null) {
                $sym->refEnv->assign($sym->refName, $value);
                return null;
            }

            // Caso 2: puntero explícito guardado como {'__ref__', '__env__'}.
            if (is_array($sym->valor) && isset($sym->valor['__ref__'], $sym->valor['__env__'])) {
                $sym->valor['__env__']->assign($sym->valor['__ref__'], $value);
                return null;
            }

            $this->errors->addSemantic("'$name' no es una referencia asignable.", $line, $col);
        } catch (\RuntimeException $e) {
            $this->errors->addSemantic($e->getMessage(), $line, $col);
        }

        return null;
    }

    public function visitCompoundAssignRule(CompoundAssignRuleContext $ctx): mixed
    {
        $name  = $ctx->ID()->getText();
        $right = $this->visit($ctx->expr());
        $line  = $ctx->ID()->getSymbol()->getLine();
        $col   = $ctx->ID()->getSymbol()->getCharPositionInLine();

        try {
            $sym   = $this->env->get($name);
            $left  = $sym->valor;
            $op    = $ctx->op->getText();

            $result = match ($op) {
                '+=' => $left + $right,
                '-=' => $left - $right,
                '*=' => $left * $right,
                '/=' => $right != 0
                    ? (is_int($left) && is_int($right)
                        ? intdiv($left, $right)
                        : $left / $right)
                    : $this->divisionByZero($name, $line, $col),
                default => null,
            };

            $this->env->assign($name, $result);
        } catch (\RuntimeException $e) {
            $this->errors->addSemantic($e->getMessage(), $line, $col);
        }

        return null;
    }

    public function visitArrayAssign1D(ArrayAssign1DContext $ctx): mixed
    {
        $name  = $ctx->ID()->getText();
        $index = (int) $this->visit($ctx->expr(0));
        $value = $this->visit($ctx->expr(1));
        $line  = $ctx->ID()->getSymbol()->getLine();
        $col   = $ctx->ID()->getSymbol()->getCharPositionInLine();

        try {
            $arr = &$this->env->getRef($name);
            if (!is_array($arr) || isset($arr[0]) && is_array($arr[0])) {
                $this->errors->addSemantic("'$name' no es un arreglo 1D.", $line, $col);
                return null;
            }
            $arr[$index] = $value;
            // si el simbolo es un parametro byRef, propagar el arreglo actualizado de inmediato
            $sym = $this->env->get($name);
            if ($sym->refName !== null) {
                $sym->refEnv->assign($sym->refName, $arr);
            }
        } catch (\RuntimeException $e) {
            $this->errors->addSemantic($e->getMessage(), $line, $col);
        }

        return null;
    }

    public function visitArrayAssign2D(ArrayAssign2DContext $ctx): mixed
    {
        $name  = $ctx->ID()->getText();
        $row   = (int) $this->visit($ctx->expr(0));
        $col_i = (int) $this->visit($ctx->expr(1));
        $value = $this->visit($ctx->expr(2));
        $line  = $ctx->ID()->getSymbol()->getLine();
        $col   = $ctx->ID()->getSymbol()->getCharPositionInLine();

        try {
            $arr = &$this->env->getRef($name);
            $arr[$row][$col_i] = $value;
            // si el simbolo es un parametro byRef, propagar el arreglo actualizado de inmediato
            $sym = $this->env->get($name);
            if ($sym->refName !== null) {
                $sym->refEnv->assign($sym->refName, $arr);
            }
        } catch (\RuntimeException $e) {
            $this->errors->addSemantic($e->getMessage(), $line, $col);
        }

        return null;
    }

    public function visitArrayAssign3D(ArrayAssign3DContext $ctx): mixed
    {
        $name  = $ctx->ID()->getText();
        $i     = (int) $this->visit($ctx->expr(0));
        $j     = (int) $this->visit($ctx->expr(1));
        $k     = (int) $this->visit($ctx->expr(2));
        $value = $this->visit($ctx->expr(3));
        $line  = $ctx->ID()->getSymbol()->getLine();
        $col   = $ctx->ID()->getSymbol()->getCharPositionInLine();

        try {
            $arr = &$this->env->getRef($name);
            $arr[$i][$j][$k] = $value;

            $sym = $this->env->get($name);
            if ($sym->refName !== null) {
                $sym->refEnv->assign($sym->refName, $arr);
            }
        } catch (\RuntimeException $e) {
            $this->errors->addSemantic($e->getMessage(), $line, $col);
        }

        return null;
    }

    // ─── sentencias de control ────────────────────────────────────────────────

    public function visitReturnStmt(ReturnStmtContext $ctx): mixed
    {
        $value = null;
        if ($ctx->exprList() !== null) {
            $exprs = $ctx->exprList()->expr();
            if (count($exprs) === 1) {
                $value = $this->visit($exprs[0]);
            } else {
                // multiple return: devuelve array
                $value = array_map(fn($e) => $this->visit($e), $exprs);
            }
        }
        return new ReturnType($value);
    }

    public function visitBreakStmt(BreakStmtContext $ctx): mixed
    {
        return new BreakType();
    }

    public function visitContinueStmt(ContinueStmtContext $ctx): mixed
    {
        return new ContinueType();
    }

    // ─── if / else ────────────────────────────────────────────────────────────

    public function visitIfStmtRule(IfStmtRuleContext $ctx): mixed
    {
        $cond = $this->visit($ctx->expr());

        if ($cond) {
            $result = $this->visit($ctx->block(0));
            if ($result instanceof FlowType) return $result;
        } else {
            // else-if
            $nestedIf = $ctx->ifStmt();
            if ($nestedIf !== null) {
                $result = $this->visit($nestedIf);
                if ($result instanceof FlowType) return $result;
            } elseif (count($ctx->block()) > 1) {
                // else block
                $result = $this->visit($ctx->block(1));
                if ($result instanceof FlowType) return $result;
            }
        }

        return null;
    }

    // ─── switch ───────────────────────────────────────────────────────────────

    public function visitSwitchStmtRule(SwitchStmtRuleContext $ctx): mixed
    {
        $subject = ($ctx->expr() !== null) ? $this->visit($ctx->expr()) : true;

        foreach ($ctx->caseClause() as $clause) {
            $result = $this->visit($clause);
            // resultado especial: el case hizo match
            if ($result === '__matched__') {
                return null;
            }
            if ($result instanceof BreakType) {
                return null;
            }
            if ($result instanceof FlowType) {
                return $result;
            }
        }

        return null;
    }

    public function visitCaseClauseRule(CaseClauseRuleContext $ctx): mixed
    {
        // obtenemos el sujeto del switch desde el visitSwitchStmtRule
        // aqui lo reevaluamos via el contexto padre
        $parent  = $ctx->parentCtx;
        $subject = ($parent instanceof SwitchStmtRuleContext && $parent->expr() !== null)
            ? $this->visit($parent->expr())
            : true;

        $matched = false;
        foreach ($ctx->exprList()->expr() as $e) {
            if ($this->visit($e) == $subject) {
                $matched = true;
                break;
            }
        }

        if (!$matched) {
            return null;
        }

        foreach ($ctx->stmt() as $s) {
            $result = $this->visit($s);
            if ($result instanceof BreakType) {
                return '__matched__';
            }
            if ($result instanceof FlowType) {
                return $result;
            }
        }

        return '__matched__';
    }

    public function visitDefaultClause(DefaultClauseContext $ctx): mixed
    {
        // el default solo se ejecuta si ningun case hizo match,
        // lo cual se controla en visitSwitchStmt recorriendo en orden
        foreach ($ctx->stmt() as $s) {
            $result = $this->visit($s);
            if ($result instanceof BreakType) {
                return '__matched__';
            }
            if ($result instanceof FlowType) {
                return $result;
            }
        }
        return '__matched__';
    }

    // ─── for ──────────────────────────────────────────────────────────────────

    public function visitForInfinite(ForInfiniteContext $ctx): mixed
    {
        while (true) {
            $result = $this->visit($ctx->block());
            if ($result instanceof BreakType) {
                break;
            }
            if ($result instanceof ReturnType) {
                return $result;
            }
            // ContinueType solo vuelve al inicio del loop
        }
        return null;
    }

    public function visitForWhile(ForWhileContext $ctx): mixed
    {
        while ($this->visit($ctx->expr())) {
            $result = $this->visit($ctx->block());
            if ($result instanceof BreakType) {
                break;
            }
            if ($result instanceof ReturnType) {
                return $result;
            }
        }
        return null;
    }

    public function visitForClassic(ForClassicContext $ctx): mixed
    {
        // crear scope propio para la variable de inicializacion del for
        $prevEnv   = $this->env;
        $this->env = new Environment($prevEnv);

        $this->visit($ctx->forInit());

        while ($this->visit($ctx->expr())) {
            $result = $this->visit($ctx->block());
            if ($result instanceof BreakType) {
                break;
            }
            if ($result instanceof ReturnType) {
                $this->env = $prevEnv;
                return $result;
            }
            $this->visit($ctx->forPost());
        }

        $this->env = $prevEnv;
        return null;
    }

    public function visitForInitShort(ForInitShortContext $ctx): mixed
    {
        return $this->visit($ctx->shortDecl());
    }

    public function visitForInitAssign(ForInitAssignContext $ctx): mixed
    {
        return $this->visit($ctx->assignment());
    }

    public function visitForPostAssign(ForPostAssignContext $ctx): mixed
    {
        return $this->visit($ctx->assignment());
    }

    public function visitForPostCompound(ForPostCompoundContext $ctx): mixed
    {
        return $this->visit($ctx->compoundAssign());
    }

    public function visitForPostInc(ForPostIncContext $ctx): mixed
    {
        $name = $ctx->ID()->getText();
        try {
            $sym = $this->env->get($name);
            $this->env->assign($name, $sym->valor + 1);
        } catch (\RuntimeException $e) {
            $this->errors->addSemantic($e->getMessage(),
                $ctx->ID()->getSymbol()->getLine(),
                $ctx->ID()->getSymbol()->getCharPositionInLine());
        }
        return null;
    }

    public function visitForPostDec(ForPostDecContext $ctx): mixed
    {
        $name = $ctx->ID()->getText();
        try {
            $sym = $this->env->get($name);
            $this->env->assign($name, $sym->valor - 1);
        } catch (\RuntimeException $e) {
            $this->errors->addSemantic($e->getMessage(),
                $ctx->ID()->getSymbol()->getLine(),
                $ctx->ID()->getSymbol()->getCharPositionInLine());
        }
        return null;
    }

    // ─── llamadas de usuario ──────────────────────────────────────────────────

    public function visitUserFuncCall(UserFuncCallContext $ctx): mixed
    {
        $name = $ctx->ID()->getText();
        $line = $ctx->ID()->getSymbol()->getLine();
        $col  = $ctx->ID()->getSymbol()->getCharPositionInLine();

        // prevenir llamada explicita a main()
        if ($name === 'main') {
            $this->errors->addSemantic('La funcion main() no puede ser llamada explicitamente.', $line, $col);
            return null;
        }

        try {
            $sym = $this->env->get($name);
            if (!($sym->valor instanceof Invocable)) {
                $this->errors->addSemantic("'$name' no es una funcion.", $line, $col);
                return null;
            }

            $args       = [];
            $funcParams = $sym->valor instanceof FuncionUsuario ? $sym->valor->params : [];
            if ($ctx->argList() !== null) {
                foreach ($ctx->argList()->expr() as $i => $exprNode) {
                    $param = $funcParams[$i] ?? null;
                    if ($param && $param['byRef'] && $exprNode instanceof IdExprContext) {
                        // arg sin & pero el parametro espera referencia:
                        // propagamos la referencia original si el simbolo ya es byRef
                        $argName = $exprNode->ID()->getText();
                        try {
                            $argSym = $this->env->get($argName);
                            if ($argSym->refName !== null) {
                                // ya es un parametro byRef: propagamos la referencia original
                                $args[] = ['__ref__' => $argSym->refName, '__env__' => $argSym->refEnv];
                            } else {
                                // variable normal: la convertimos en referencia
                                $args[] = ['__ref__' => $argName, '__env__' => $this->env];
                            }
                        } catch (\RuntimeException $e) {
                            $args[] = $this->visit($exprNode);
                        }
                    } else {
                        $args[] = $this->visit($exprNode);
                    }
                }
            }

            if ($sym->valor->getArity() !== count($args)) {
                $this->errors->addSemantic(
                    "'$name' espera {$sym->valor->getArity()} argumento(s), se recibieron " . count($args) . '.',
                    $line, $col
                );
                return null;
            }

            return $sym->valor->invoke($this, $args);
        } catch (\RuntimeException $e) {
            $this->errors->addSemantic($e->getMessage(), $line, $col);
            return null;
        }
    }

    public function visitCallExprWrap(CallExprWrapContext $ctx): mixed
    {
        return $this->visit($ctx->callExpr());
    }

    public function visitCastExpr(CastExprContext $ctx): mixed
    {
        $target = $ctx->typeRef()->getText();
        $value  = $this->visit($ctx->expr());

        return match ($target) {
            'int', 'int32', 'int64' => (int) $value,
            'float32', 'float64'    => (float) $value,
            'bool'                  => (bool) $value,
            'string'                => is_null($value) ? '' : (string) $value,
            'rune'                  => is_string($value)
                ? (strlen($value) > 0 ? ord($value[0]) : 0)
                : (int) $value,
            default                 => $value,
        };
    }

    public function visitArgList(ArgListContext $ctx): array
    {
        return array_map(fn($e) => $this->visit($e), $ctx->expr());
    }

    // ─── expresiones aritmeticas y logicas ────────────────────────────────────

    public function visitPrimaryExpr(PrimaryExprContext $ctx): mixed
    {
        return $this->visit($ctx->primary());
    }

    public function visitIdExpr(IdExprContext $ctx): mixed
    {
        $name = $ctx->ID()->getText();
        $line = $ctx->ID()->getSymbol()->getLine();
        $col  = $ctx->ID()->getSymbol()->getCharPositionInLine();

        try {
            return $this->env->get($name)->valor;
        } catch (\RuntimeException $e) {
            $this->errors->addSemantic($e->getMessage(), $line, $col);
            return null;
        }
    }

    public function visitGroupExpr(GroupExprContext $ctx): mixed
    {
        return $this->visit($ctx->expr());
    }

    public function visitNegExpr(NegExprContext $ctx): mixed
    {
        return -$this->visit($ctx->expr());
    }

    public function visitNotExpr(NotExprContext $ctx): mixed
    {
        return !$this->visit($ctx->expr());
    }

    public function visitMulExpr(MulExprContext $ctx): mixed
    {
        $left  = $this->visit($ctx->expr(0));
        $right = $this->visit($ctx->expr(1));
        $op    = $ctx->op->getText();

        if ($op === '*') {
            // int32 * string => string repetition (spec multiplication table)
            if (is_int($left) && is_string($right)) return str_repeat($right, max(0, $left));
            if (is_string($left) && is_int($right)) return str_repeat($left, max(0, $right));
            return $left * $right;
        }
        if ($op === '/') {
            if ($right == 0) return $this->divisionByZero('expresion', $ctx->op->getLine(), $ctx->op->getCharPositionInLine());
            return (is_int($left) && is_int($right)) ? intdiv($left, $right) : $left / $right;
        }
        if ($op === '%') {
            if ($right == 0) return $this->divisionByZero('expresion', $ctx->op->getLine(), $ctx->op->getCharPositionInLine());
            return $left % $right;
        }
        return null;
    }

    public function visitAddExpr(AddExprContext $ctx): mixed
    {
        $left  = $this->visit($ctx->expr(0));
        $right = $this->visit($ctx->expr(1));
        $op    = $ctx->op->getText();

        // string + string => concatenacion (spec sum table)
        if ($op === '+' && is_string($left) && is_string($right)) {
            return $left . $right;
        }

        return $op === '+' ? $left + $right : $left - $right;
    }

    public function visitRelExpr(RelExprContext $ctx): mixed
    {
        $left  = $this->visit($ctx->expr(0));
        $right = $this->visit($ctx->expr(1));
        $op    = $ctx->op->getText();

        // nil comparisons return nil
        if (is_null($left) || is_null($right)) {
            if ($op === '==' || $op === '!=') {
                return null;
            }
            return null;
        }

        return match ($op) {
            '<'  => $left <  $right,
            '<=' => $left <= $right,
            '>'  => $left >  $right,
            '>=' => $left >= $right,
            '==' => $left === $right,
            '!=' => $left !== $right,
            default => false,
        };
    }

    public function visitAndExpr(AndExprContext $ctx): mixed
    {
        // cortocircuito: si el lado izquierdo es falso no se evalua el derecho
        $left = $this->visit($ctx->expr(0));
        if (!$left) {
            return false;
        }
        return (bool) $this->visit($ctx->expr(1));
    }

    public function visitOrExpr(OrExprContext $ctx): mixed
    {
        // cortocircuito: si el lado izquierdo es verdadero no se evalua el derecho
        $left = $this->visit($ctx->expr(0));
        if ($left) {
            return true;
        }
        return (bool) $this->visit($ctx->expr(1));
    }

    // ─── punteros ─────────────────────────────────────────────────────────────

    public function visitRefExpr(RefExprContext $ctx): mixed
    {
        // devuelve una referencia PHP al valor del simbolo
        $name = $ctx->ID()->getText();
        return ['__ref__' => $name, '__env__' => $this->env];
    }

    public function visitDerefExpr(DerefExprContext $ctx): mixed
    {
        $name = $ctx->ID()->getText();
        try {
            $sym = $this->env->get($name);
            $ref = $sym->valor;
            // si es un array de referencia con __ref__
            if (is_array($ref) && isset($ref['__ref__'])) {
                return $ref['__env__']->get($ref['__ref__'])->valor;
            }
            return $ref;
        } catch (\RuntimeException $e) {
            $this->errors->addSemantic($e->getMessage(),
                $ctx->ID()->getSymbol()->getLine(),
                $ctx->ID()->getSymbol()->getCharPositionInLine());
            return null;
        }
    }

    // ─── literales ────────────────────────────────────────────────────────────

    public function visitIntLit(IntLitContext $ctx): mixed
    {
        return (int) $ctx->INT_LIT()->getText();
    }

    public function visitFloatLit(FloatLitContext $ctx): mixed
    {
        return (float) $ctx->FLOAT_LIT()->getText();
    }

    public function visitStringLit(StringLitContext $ctx): mixed
    {
        $raw = $ctx->STRING_LIT()->getText();
        // quitar comillas y procesar escapes basicos
        $inner = substr($raw, 1, strlen($raw) - 2);
        return stripcslashes($inner);
    }

    public function visitRuneLit(RuneLitContext $ctx): mixed
    {
        $raw   = $ctx->RUNE_LIT()->getText();
        $inner = substr($raw, 1, strlen($raw) - 2);
        if ($inner[0] === '\\') {
            $inner = stripcslashes($inner);
        }
        return ord($inner[0]);
    }

    public function visitTrueLit(TrueLitContext $ctx): mixed
    {
        return true;
    }

    public function visitFalseLit(FalseLitContext $ctx): mixed
    {
        return false;
    }

    public function visitNilLit(NilLitContext $ctx): mixed
    {
        return null;
    }

    public function visitArrayLit1D(ArrayLit1DContext $ctx): mixed
    {
        $exprs = $ctx->expr();
        return array_map(fn($e) => $this->visit($e), $exprs);
    }

    public function visitArrayLit2D(ArrayLit2DContext $ctx): mixed
    {
        // INT_LIT(0) = filas, INT_LIT(1) = columnas; las expresiones llegan en orden fila por fila
        $rows   = (int) $ctx->INT_LIT(0)->getText();
        $cols   = (int) $ctx->INT_LIT(1)->getText();
        $flat   = array_map(fn($e) => $this->visit($e), $ctx->expr());
        $result = [];
        for ($r = 0; $r < $rows; $r++) {
            $result[] = array_slice($flat, $r * $cols, $cols);
        }
        return $result;
    }

    public function visitArrayLit3D(ArrayLit3DContext $ctx): mixed
    {
        // INT_LIT(0)=d1, INT_LIT(1)=d2, INT_LIT(2)=d3; expr() llega en orden lineal.
        $d1     = (int) $ctx->INT_LIT(0)->getText();
        $d2     = (int) $ctx->INT_LIT(1)->getText();
        $d3     = (int) $ctx->INT_LIT(2)->getText();
        $flat   = array_map(fn($e) => $this->visit($e), $ctx->expr());
        $result = [];
        $idx    = 0;

        for ($i = 0; $i < $d1; $i++) {
            $plane = [];
            for ($j = 0; $j < $d2; $j++) {
                $plane[] = array_slice($flat, $idx, $d3);
                $idx += $d3;
            }
            $result[] = $plane;
        }

        return $result;
    }

    // ─── acceso a arreglos ────────────────────────────────────────────────────

    public function visitArrayAccess1D(ArrayAccess1DContext $ctx): mixed
    {
        $name  = $ctx->ID()->getText();
        $index = (int) $this->visit($ctx->expr(0));
        $line  = $ctx->ID()->getSymbol()->getLine();
        $col   = $ctx->ID()->getSymbol()->getCharPositionInLine();

        try {
            $arr = $this->env->get($name)->valor;
            return $arr[$index] ?? null;
        } catch (\RuntimeException $e) {
            $this->errors->addSemantic($e->getMessage(), $line, $col);
            return null;
        }
    }

    public function visitArrayAccess2D(ArrayAccess2DContext $ctx): mixed
    {
        $name  = $ctx->ID()->getText();
        $row   = (int) $this->visit($ctx->expr(0));
        $col_i = (int) $this->visit($ctx->expr(1));
        $line  = $ctx->ID()->getSymbol()->getLine();
        $col   = $ctx->ID()->getSymbol()->getCharPositionInLine();

        try {
            $arr = $this->env->get($name)->valor;
            return $arr[$row][$col_i] ?? null;
        } catch (\RuntimeException $e) {
            $this->errors->addSemantic($e->getMessage(), $line, $col);
            return null;
        }
    }

    public function visitArrayAccess3D(ArrayAccess3DContext $ctx): mixed
    {
        $name = $ctx->ID()->getText();
        $i    = (int) $this->visit($ctx->expr(0));
        $j    = (int) $this->visit($ctx->expr(1));
        $k    = (int) $this->visit($ctx->expr(2));
        $line = $ctx->ID()->getSymbol()->getLine();
        $col  = $ctx->ID()->getSymbol()->getCharPositionInLine();

        try {
            $arr = $this->env->get($name)->valor;
            return $arr[$i][$j][$k] ?? null;
        } catch (\RuntimeException $e) {
            $this->errors->addSemantic($e->getMessage(), $line, $col);
            return null;
        }
    }

    // ─── builtins ─────────────────────────────────────────────────────────────

    public function visitBuiltinStmt(BuiltinStmtContext $ctx): mixed
    {
        return $this->visit($ctx->builtinCall());
    }

    public function visitBuiltinExpr(BuiltinExprContext $ctx): mixed
    {
        return $this->visit($ctx->builtinCall());
    }

    public function visitBuiltinLen(BuiltinLenContext $ctx): mixed
    {
        $val = $this->visit($ctx->expr());
        if (is_string($val)) return strlen($val);
        if (is_array($val))  return count($val);
        $this->errors->addSemantic('len() requiere string o arreglo.',
            $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine());
        return 0;
    }

    public function visitBuiltinNow(BuiltinNowContext $ctx): mixed
    {
        return date('Y-m-d H:i:s');
    }

    public function visitBuiltinSubstr(BuiltinSubstrContext $ctx): mixed
    {
        $str   = $this->visit($ctx->expr(0));
        $start = (int) $this->visit($ctx->expr(1));
        $len   = (int) $this->visit($ctx->expr(2));
        if (!is_string($str)) {
            $this->errors->addSemantic('substr() requiere string como primer argumento.',
                $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine());
            return '';
        }
        return substr($str, $start, $len);
    }

    public function visitBuiltinTypeOf(BuiltinTypeOfContext $ctx): mixed
    {
        $exprNode = $ctx->expr();
        $astType = $this->typeFromExprAst($exprNode);
        if ($astType !== null) {
            return $astType;
        }
        return $this->inferType($this->visit($exprNode));
    }

    // ─── utiles ───────────────────────────────────────────────────────────────

    // determina el tipo Golampi a partir del nodo AST de la expresion (antes de evaluar)
    // retorna null si no puede determinarlo (el llamador usa inferType como fallback)
    private function typeFromExprAst(mixed $exprNode): ?string
    {
        if ($exprNode === null) return null;

        if ($exprNode instanceof CastExprContext) {
            return $exprNode->typeRef()->getText();
        }

        // variable: propagar el tipo declarado del simbolo fuente
        if ($exprNode instanceof IdExprContext) {
            $name = $exprNode->ID()->getText();
            try {
                return $this->env->get($name)->tipo;
            } catch (\RuntimeException $e) {
                return null;
            }
        }

        // literal primario
        if ($exprNode instanceof PrimaryExprContext) {
            $p = $exprNode->primary();
            if ($p instanceof IntLitContext)   return 'int';
            if ($p instanceof FloatLitContext) return 'float64';
            if ($p instanceof RuneLitContext)  return 'int32';
            if ($p instanceof TrueLitContext || $p instanceof FalseLitContext) return 'bool';
            if ($p instanceof StringLitContext) return 'string';
            if ($p instanceof ArrayLit1DContext) {
                return '[' . $p->INT_LIT()->getText() . ']' . $p->typeRef()->getText();
            }
            if ($p instanceof ArrayLit2DContext) {
                $dims = $p->INT_LIT();
                return '[' . $dims[0]->getText() . '][' . $dims[1]->getText() . ']' . $p->typeRef()->getText();
            }
            if ($p instanceof ArrayLit3DContext) {
                $dims = $p->INT_LIT();
                return '[' . $dims[0]->getText() . '][' . $dims[1]->getText() . '][' . $dims[2]->getText() . ']' . $p->typeRef()->getText();
            }
        }

        return null;
    }

    // valor por defecto segun tipo de Golampi (soporta tipos array como [5]int, [2][3]float32)
    private function defaultValue(string $type): mixed
    {
        if (str_starts_with($type, '[')) {
            // extraer tamaño y tipo elemento: [N]elemType
            preg_match('/^\[(\d+)\](.+)$/', $type, $m);
            if ($m) {
                $size     = (int) $m[1];
                $elemType = $m[2];
                return array_fill(0, $size, $this->defaultValue($elemType));
            }
        }
        return match ($type) {
            'int', 'int32', 'int64', 'rune' => 0,
            'float32', 'float64'            => 0.0,
            'bool'                          => false,
            'string'                        => '',
            default                         => null,
        };
    }

    // infiere el tipo PHP -> tipo Golampi para declaraciones cortas y typeOf()
    public function inferType(mixed $value): string
    {
        if (is_bool($value))   return 'bool';   // antes de is_int porque bool es subtype de int en PHP
        if (is_int($value))    return 'int';
        if (is_float($value))  return 'float64';
        if (is_string($value)) return 'string';
        if (is_array($value)) {
            $count = count($value);
            if ($count > 0 && is_array($value[0])) {
                $inner = $this->inferArrayElemType($value[0]);
                return '[' . $count . '][' . count($value[0]) . ']' . $inner;
            }
            $inner = $count > 0 ? $this->inferArrayElemType($value) : 'int32';
            return '[' . $count . ']' . $inner;
        }
        return 'nil';
    }

    private function inferArrayElemType(array $arr): string
    {
        foreach ($arr as $v) {
            if (!is_array($v)) {
                if (is_bool($v))   return 'bool';
                if (is_int($v))    return 'int';
                if (is_float($v))  return 'float64';
                if (is_string($v)) return 'string';
            }
        }
        return 'int';
    }

    // convierte un valor PHP a string para impresion
    public function valueToString(mixed $val): string
    {
        if (is_bool($val))  return $val ? 'true' : 'false';
        if (is_null($val))  return '<nil>';
        if (is_array($val)) return '[' . implode(' ', array_map([$this, 'valueToString'], $val)) . ']';
        if (is_float($val)) {
            // evitar notacion cientifica y mostrar bien los decimales
            $s = rtrim(number_format($val, 10, '.', ''), '0');
            return rtrim($s, '.') ?: '0';
        }
        return (string) $val;
    }

    private function divisionByZero(string $id, int $line, int $col): null
    {
        $this->errors->addSemantic("Division por cero en '$id'.", $line, $col);
        return null;
    }

    // validar compatibilidad de tipos en asignaciones y declaraciones
    private function isTypeCompatible(string $declaredType, mixed $value): bool
    {
        // nil es siempre incompatible en declaraciones tipadas (a menos que sea opcional)
        if ($value === null) {
            return false; // no permitir asignar nil a variable tipada
        }

        // chequear si el valor es un array y el tipo declarado es un array
        if (is_array($value)) {
            // si el tipo declarado comienza con '[', es un tipo array
            return strpos(trim($declaredType), '[') === 0;
        }

        // mapear tipos de Golampi a tipos PHP para validacion
        $declaredType = strtolower(trim($declaredType));
        $actualType = null;

        if (is_bool($value))   $actualType = 'bool';
        elseif (is_int($value))    $actualType = 'int32';
        elseif (is_float($value))  $actualType = 'float32';
        elseif (is_string($value)) $actualType = 'string';

        // permitir promociones: int32 -> rune, int (ambiguos), int64, float64
        $compatibleTypes = [
            'int32' => ['int32', 'rune', 'int', 'int64'],
            'float32' => ['float32', 'float64'],
            'bool' => ['bool'],
            'rune' => ['rune', 'int32', 'int', 'int64'],
            'string' => ['string'],
        ];

        if (isset($compatibleTypes[$declaredType])) {
            return in_array($actualType, $compatibleTypes[$declaredType]);
        }

        return false;
    }

    public function getSymbolTable(): array
    {
        return $this->symbolTable;
    }
}
