<?php

use Context\DigitContext;

class EvalListener extends GrammarBaseListener
{
    private $stack = [];

    public function getResult()
    {
        return end($this->stack);
    }

    public function exitDigit(DigitContext $ctx): void
    {
        $val = (int) $ctx->DIGIT()->getText();
        array_push($this->stack, $val);
    }

    public function exitEpsSum(Context\EpsSumContext $ctx): void
    {
        $right = array_pop($this->stack);
        $left  = array_pop($this->stack);
        array_push($this->stack, $left + $right);
    }

    public function exitEpsMul(Context\EpsMulContext $ctx): void
    {
        $right = array_pop($this->stack);
        $left  = array_pop($this->stack);
        array_push($this->stack, $left * $right);
    }
}
