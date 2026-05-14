<?php

use Context\BContext;
use Context\BpContext;

class EvalVisitor extends GrammarBaseVisitor
{
    public function visitB(BContext $ctx)
    {
        // b -> '1" bp
        $value = 1;
        return $this->visitBpInherits($ctx->bp(), $value);
    }

    private function visitBpInherits(?BpContext $ctx, int $acc)
    {
        // bp -> ε
        if ($ctx === null || $ctx->getChildCount() === 0) {
            return $acc;
        }

        $bit = $ctx->getChild(0)->getText();

        if ($bit === '0')
            $acc = $acc * 2;
        else
            $acc = $acc * 2 + 1;

        return $this->visitBpInherits($ctx->bp(), $acc);
    }
}
