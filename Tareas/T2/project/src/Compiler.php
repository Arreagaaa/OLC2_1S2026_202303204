<?php
class Compiler extends GrammarBaseVisitor
{
    public $code;
    public function __construct()
    {
        $this->code = new C3DGenerator();
    }
    public function visitProgram($ctx)
    {
        foreach ($ctx->stmt() as $stmt) {
            $this->visit($stmt);
        }
        return $this->code;
    }
    public function visitAssignStatement($ctx)
    {
        $varName = $ctx->ID()->getText();
        $temp = $this->visit($ctx->e());
        $this->code->emitCopy($varName, $temp);
    }
    public function visitPrintStatement($ctx)
    {
        $varName = $ctx->ID()->getText();
        $this->code->emitPrint($varName);
    }
    public function visitAddExpr($ctx)
    {
        $left  = $this->visit($ctx->e(0));
        $right = $this->visit($ctx->term()); // ← term, no e(1)
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
        $right = $this->visit($ctx->factor()); // ← factor, no term(1)
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
    public function visitIfStatement($ctx)
    {
        $condTemp  = $this->visit($ctx->cond());
        $labelElse = $this->code->newLabel();
        $labelEnd  = $this->code->newLabel();
        $this->code->emitIfFalse($condTemp, $labelElse);
        $this->visit($ctx->block(0));
        if ($ctx->block(1) !== null) {
            $this->code->emitGoto($labelEnd);
            $this->code->emitLabel($labelElse);
            $this->visit($ctx->block(1));
            $this->code->emitLabel($labelEnd);
        } else {
            $this->code->emitLabel($labelElse);
        }
    }
    public function visitCondition($ctx)
    {
        $left  = $this->visit($ctx->e(0));
        $right = $this->visit($ctx->e(1));
        $op    = $ctx->op->getText();
        $temp  = $this->code->newTemp();
        $this->code->emit($temp, $left, $op, $right);
        return $temp;
    }
}
