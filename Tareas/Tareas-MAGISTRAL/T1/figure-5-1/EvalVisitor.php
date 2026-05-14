<?php

use Context\AddContext;
use Context\IntContext;
use Context\MulContext;
use Context\ParContext;

class EvalVisitor extends GrammarBaseVisitor
{
    public function visitAdd(AddContext $ctx)
    {
        $left  = $this->visit($ctx->e());
        $right = $this->visit($ctx->t());
        
        return $left + $right;
    }

    public function visitMul(MulContext $ctx)
    {
        $left  = $this->visit($ctx->t());
        $right = $this->visit($ctx->f());
        
        return $left * $right;
    }

    public function visitInt(IntContext $ctx)
    {
        return (int) $ctx->DIGIT()->getText();
    }

    public function visitPar(ParContext $ctx)
    {
        return $this->visit($ctx->e());
    }
}
