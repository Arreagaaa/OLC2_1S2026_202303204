<?php

use Context\TContext;
use Context\BContext;
use Context\CContext;
use Context\NContext;

class EvalVisitor extends GrammarBaseVisitor
{
    private $currenType;

    public function visitT(TContext $ctx)
    {
        $this->currentType = $this->visit($ctx->b());
        return $this->visit($ctx->c());
    }

    public function visitB(BContext $ctx)
    {
        return $ctx->getText() === 'int' ? 'integer' : "float";
    }

    public function visitC(CContext $ctx)
    {
        if ($ctx->c() != null) {
            $currentDimension = $ctx->n()->NUM()->getText();
            $nestedDimension = $this->visit($ctx->c());
            return "array($currentDimension, $nestedDimension)";
        }

        return $this->currentType;
    }

    public function visitN(NContext $ctx)
    {
        return $ctx->NUM()->getText();
    }
}
