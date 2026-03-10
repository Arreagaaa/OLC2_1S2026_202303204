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
use Context\ConstDeclRuleContext;
use Context\ShortDeclRuleContext;
use Context\SimpleAssignContext;
use Context\CompoundAssignRuleContext;
use Context\ArrayAssign1DContext;
use Context\ArrayAssign2DContext;

use Context\PrimaryExprContext;
use Context\IdExprContext;
use Context\CallExprWrapContext;
use Context\FmtPrintlnExprContext;
use Context\ArrayAccess1DContext;
use Context\ArrayAccess2DContext;
use Context\RefExprContext;
use Context\DerefExprContext;
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

use Context\UserFuncCallContext;
use Context\FmtPrintlnCallContext;
use Context\ParamDeclContext;
use Context\ParamListContext;
use Context\ArgListContext;

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
        $params = [];

        if ($ctx->paramList() !== null) {
            $params = $this->visit($ctx->paramList());
        }

        $returnTypes = [];
        if ($ctx->returnType() !== null) {
            // se resuelve en sprint 4; por ahora solo registra el nodo
        }

        $funcion = new FuncionUsuario($ctx, $this->env, $params, $returnTypes);

        $sym = new Symbol(
            $name,
            'funcion',
            $funcion,
            Symbol::CLASE_FUNCION,
            'global',
            $ctx->ID()->getSymbol()->getLine(),
            $ctx->ID()->getSymbol()->getCharPositionInLine()
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

        $sym = new Symbol($name, $type, $value, Symbol::CLASE_CONSTANTE, 'local', $line, $col);
        $this->env->declare($name, $sym);
        $this->symbolTable[] = $sym;

        return null;
    }

    public function visitShortDeclRule(ShortDeclRuleContext $ctx): mixed
    {
        $ids   = $ctx->idList()->ID();
        $exprs = $ctx->exprList()->expr();

        foreach ($ids as $i => $idNode) {
            $name  = $idNode->getText();
            $value = isset($exprs[$i]) ? $this->visit($exprs[$i]) : null;
            $type  = $this->inferType($value);
            $line  = $idNode->getSymbol()->getLine();
            $col   = $idNode->getSymbol()->getCharPositionInLine();

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

        try {
            $sym = $this->env->get($name);
            if (!($sym->valor instanceof Invocable)) {
                $this->errors->addSemantic("'$name' no es una funcion.", $line, $col);
                return null;
            }

            $args = [];
            if ($ctx->argList() !== null) {
                $args = $this->visit($ctx->argList());
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

        return match ($op) {
            '<'  => $left <  $right,
            '<=' => $left <= $right,
            '>'  => $left >  $right,
            '>=' => $left >= $right,
            '==' => $left == $right,
            '!=' => $left != $right,
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

    // ─── utiles ───────────────────────────────────────────────────────────────

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

    // infiere el tipo PHP -> tipo Golampi para declaraciones cortas
    private function inferType(mixed $value): string
    {
        if (is_int($value))    return 'int32';
        if (is_float($value))  return 'float32';
        if (is_bool($value))   return 'bool';
        if (is_string($value)) return 'string';
        return 'nil';
    }

    // convierte un valor PHP a string para impresion
    public function valueToString(mixed $val): string
    {
        if (is_bool($val))  return $val ? 'true' : 'false';
        if (is_null($val))  return 'nil';
        if (is_array($val)) return '[' . implode(' ', array_map([$this, 'valueToString'], $val)) . ']';
        return (string) $val;
    }

    private function divisionByZero(string $id, int $line, int $col): null
    {
        $this->errors->addSemantic("Division por cero en '$id'.", $line, $col);
        return null;
    }

    public function getSymbolTable(): array
    {
        return $this->symbolTable;
    }
}
