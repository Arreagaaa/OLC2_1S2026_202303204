<?php

use Context\SumresContext;
use Context\MuldivContext;
use Context\NumContext;
use Context\idContext;

require_once __DIR__ . '/Node.php';

class ASTListener extends GrammarBaseListener
{
    private $id = 0;
    private $stack = [];
    private $history = [];

    private function getNode($label, $left = null, $right = null)
    {
        $signature = $label;
        if ($left !== null && $right !== null) {
            $signature .= '_' . $left->id . '_' . $right->id;
        }

        if (isset($this->history[$signature])) {
            return $this->history[$signature];
        }

        $node = new Node($this->id++, $label, $left, $right);

        $this->history[$signature] = $node;
        return $node;
    }

    public function getResult()
    {
        return end($this->stack);
    }

    public function exitSumres(SumresContext $context): void
    {
        $right = array_pop($this->stack);
        $left = array_pop($this->stack);
        $node = $this->getNode($context->op->getText(), $left, $right);
        array_push($this->stack, $node);
    }

    public function exitMuldiv(MuldivContext $context): void
    {
        $right = array_pop($this->stack);
        $left = array_pop($this->stack);
        $node = $this->getNode($context->op->getText(), $left, $right);
        array_push($this->stack, $node);
    }

    public function exitNum(NumContext $context): void
    {
        $node = $this->getNode($context->NUM()->getText());
        array_push($this->stack, $node);
    }

    public function exitId(idContext $context): void
    {
        $node = $this->getNode($context->ID()->getText());
        array_push($this->stack, $node);
    }
}
