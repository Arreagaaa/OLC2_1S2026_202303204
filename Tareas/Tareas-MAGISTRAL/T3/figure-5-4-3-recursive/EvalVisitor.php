<?php

use Context\BContext;

class EvalVisitor extends GrammarBaseVisitor
{
    public function visitB(BContext $ctx)
    {
        // base case: b->'1'
        if ($ctx->getChildCount() === 1) {
            return 1;
        }

        // recursive case: b -> b '0' | b '1'
        $leftValue = $this->visit($ctx->b());
        $bit = $ctx->getChild(1)->getText();

        if ($bit === '0')
            return $leftValue * 2;
        return $leftValue * 2 + 1;
    }
}
