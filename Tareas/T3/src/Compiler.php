<?php
class Compiler extends GrammarBaseVisitor
{
    public $code;

    public function __construct()
    {
        $this->code = new C3DGenerator();
    }

    // ─────────────────────────────────────────
    // Programa
    // ─────────────────────────────────────────
    public function visitProgram($ctx)
    {
        foreach ($ctx->stmt() as $stmt) {
            $this->visit($stmt);
        }
        return $this->code;
    }

    // ─────────────────────────────────────────
    // Sentencias
    // ─────────────────────────────────────────
    public function visitAssignStatement($ctx)
    {
        $varName = $ctx->ID()->getText();
        $temp    = $this->visit($ctx->e());
        $this->code->emitCopy($varName, $temp);
    }

    public function visitPrintStatement($ctx)
    {
        $varName = $ctx->ID()->getText();
        $this->code->emitPrint($varName);
    }

    public function visitPrintStringStatement($ctx)
    {
        $str = $ctx->STRING()->getText(); // incluye las comillas
        // Normalizar comillas tipográficas a comillas ASCII para evitar
        // que el parser/entrada con “…” produzca literales sin comillas
        // y causen errores en tiempo de ejecución (Variable no definida).
        $str = str_replace(["“", "”", "‘", "’"], ['"', '"', "'", "'"], $str);
        $this->code->emitPrint($str);
    }

    // ─────────────────────────────────────────
    // If-Else
    // ─────────────────────────────────────────
    public function visitIfStatement($ctx)
    {
        $labelTrue  = $this->code->newLabel(); // entrada al bloque true
        $labelFalse = $this->code->newLabel(); // entrada al bloque false / fin
        $labelEnd   = $this->code->newLabel(); // fin del if-else

        // Genera saltos de cortocircuito para la condición
        $this->visitCondWithJumps($ctx->cond(), $labelTrue, $labelFalse);

        // Bloque TRUE
        $this->code->emitLabel($labelTrue);
        $this->visit($ctx->block(0));

        if ($ctx->block(1) !== null) {
            // Hay else
            $this->code->emitGoto($labelEnd);
            $this->code->emitLabel($labelFalse);
            $this->visit($ctx->block(1));
            $this->code->emitLabel($labelEnd);
        } else {
            $this->code->emitLabel($labelFalse);
        }
    }

    // ─────────────────────────────────────────
    // Condiciones con cortocircuito
    // Genera saltos directos a labelTrue / labelFalse
    // ─────────────────────────────────────────

    /**
     * Dispatch según el tipo de nodo de condición.
     */
    private function visitCondWithJumps($ctx, $labelTrue, $labelFalse)
    {
        $type = get_class($ctx);

        if (str_ends_with($type, 'CondOrExprContext')) {
            $this->visitCondOrWithJumps($ctx, $labelTrue, $labelFalse);
        } elseif (str_ends_with($type, 'CondAndPassContext')) {
            $this->visitCondWithJumps($ctx->condAnd(), $labelTrue, $labelFalse);
        } elseif (str_ends_with($type, 'CondAndExprContext')) {
            $this->visitCondAndWithJumps($ctx, $labelTrue, $labelFalse);
        } elseif (str_ends_with($type, 'CondRelPassContext')) {
            $this->visitCondWithJumps($ctx->condRel(), $labelTrue, $labelFalse);
        } elseif (str_ends_with($type, 'CondRelExprContext')) {
            $this->visitCondRelWithJumps($ctx, $labelTrue, $labelFalse);
        } elseif (str_ends_with($type, 'CondGroupExprContext')) {
            $this->visitCondWithJumps($ctx->cond(), $labelTrue, $labelFalse);
        } else {
            // Fallback genérico
            $temp = $this->visit($ctx);
            $this->code->emitIfTrue($temp, $labelTrue);
            $this->code->emitGoto($labelFalse);
        }
    }

    /**
     * OR con cortocircuito:
     *   si la parte izquierda es TRUE → salta directo a labelTrue
     *   si no, evalúa la derecha
     */
    private function visitCondOrWithJumps($ctx, $labelTrue, $labelFalse)
    {
        $labelNextRight = $this->code->newLabel();

        // Izquierda: si es true salta a true; si no, evalúa derecha
        $this->visitCondWithJumps($ctx->cond(), $labelTrue, $labelNextRight);

        $this->code->emitLabel($labelNextRight);

        // Derecha
        $this->visitCondWithJumps($ctx->condAnd(), $labelTrue, $labelFalse);
    }

    /**
     * AND con cortocircuito:
     *   si la parte izquierda es FALSE → salta directo a labelFalse
     *   si no, evalúa la derecha
     */
    private function visitCondAndWithJumps($ctx, $labelTrue, $labelFalse)
    {
        $labelNextRight = $this->code->newLabel();

        // Izquierda: si es false salta a false; si no, evalúa derecha
        $this->visitCondWithJumps($ctx->condAnd(), $labelNextRight, $labelFalse);

        $this->code->emitLabel($labelNextRight);

        // Derecha
        $this->visitCondWithJumps($ctx->condRel(), $labelTrue, $labelFalse);
    }

    /**
     * Condición relacional simple: evalúa ambos lados y emite salto condicional.
     */
    private function visitCondRelWithJumps($ctx, $labelTrue, $labelFalse)
    {
        $left  = $this->visit($ctx->e(0));
        $right = $this->visit($ctx->e(1));
        $op    = $ctx->op->getText();
        $temp  = $this->code->newTemp();
        $this->code->emit($temp, $left, $op, $right);
        $this->code->emitIfTrue($temp, $labelTrue);
        $this->code->emitGoto($labelFalse);
    }

    // ─────────────────────────────────────────
    // Visitors de condición (para uso general, sin jumps)
    // ─────────────────────────────────────────
    public function visitCondOrExpr($ctx)
    {
        $left  = $this->visit($ctx->cond());
        $right = $this->visit($ctx->condAnd());
        $temp  = $this->code->newTemp();
        $this->code->emit($temp, $left, '||', $right);
        return $temp;
    }

    public function visitCondAndPass($ctx)
    {
        return $this->visit($ctx->condAnd());
    }

    public function visitCondAndExpr($ctx)
    {
        $left  = $this->visit($ctx->condAnd());
        $right = $this->visit($ctx->condRel());
        $temp  = $this->code->newTemp();
        $this->code->emit($temp, $left, '&&', $right);
        return $temp;
    }

    public function visitCondRelPass($ctx)
    {
        return $this->visit($ctx->condRel());
    }

    public function visitCondRelExpr($ctx)
    {
        $left  = $this->visit($ctx->e(0));
        $right = $this->visit($ctx->e(1));
        $op    = $ctx->op->getText();
        $temp  = $this->code->newTemp();
        $this->code->emit($temp, $left, $op, $right);
        return $temp;
    }

    public function visitCondGroupExpr($ctx)
    {
        return $this->visit($ctx->cond());
    }

    // ─────────────────────────────────────────
    // Expresiones aritméticas
    // ─────────────────────────────────────────
    public function visitAddExpr($ctx)
    {
        $left  = $this->visit($ctx->e());
        $right = $this->visit($ctx->term());
        $op    = $ctx->op->getText();
        $temp  = $this->code->newTemp();
        $this->code->emit($temp, $left, $op, $right);
        return $temp;
    }

    public function visitTermExpr($ctx)
    {
        return $this->visit($ctx->term());
    }

    public function visitMulExpr($ctx)
    {
        $left  = $this->visit($ctx->term(0));
        $right = $this->visit($ctx->factor());
        $op    = $ctx->op->getText();
        $temp  = $this->code->newTemp();
        $this->code->emit($temp, $left, $op, $right);
        return $temp;
    }

    public function visitFactorExpr($ctx)
    {
        return $this->visit($ctx->factor());
    }

    public function visitGroupExpr($ctx)
    {
        return $this->visit($ctx->e());
    }

    public function visitIntExpr($ctx)
    {
        return $ctx->INT()->getText();
    }

    public function visitIdExpr($ctx)
    {
        return $ctx->ID()->getText();
    }
}
