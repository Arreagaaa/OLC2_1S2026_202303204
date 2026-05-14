<?php

use Context\SumresContext;
use Context\NumContext;
use Context\IdContext;
use Context\MuldivContext;

require_once  __DIR__ . '/Node.php';

class ASTListener extends GrammarBaseListener
{
    private $id = 0;
    private $stack = [];

    public function getResult()
    {
        return end($this->stack);
    }

    public function exitSumres(SumresContext $ctx): void
    {
        $right = array_pop($this->stack);
        $left = array_pop($this->stack);
        
        $node = new Node($this->id++, $ctx->op->getText(), $left, $right);
        array_push($this->stack, $node);  
    }

    public function exitMuldiv(MuldivContext $ctx): void
    {
        $right = array_pop($this->stack);
        $left = array_pop($this->stack);
        
        $node = new Node($this->id++, $ctx->op->getText(), $left, $right);
        array_push($this->stack, $node);  
    }

    public function exitNum(NumContext $ctx) : void{
        $node = new Node($this->id++, $ctx->getText());
        array_push($this->stack, $node);
    }

    public function exitId(IdContext $ctx) : void{
        $node = new Node($this->id++, $ctx->getText());
        array_push($this->stack, $node);
    }
}