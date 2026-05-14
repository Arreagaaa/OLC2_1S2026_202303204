<?php

use Context\LineContext;
use Context\AssignContext;
use Context\PrintExprContext;
use Context\AddSubContext;
use Context\MuldivContext;
use Context\UminusContext;
use Context\ParensContext;
use Context\NumberContext;
use Context\NameContext;

class EvalVisitor extends GrammarBaseVisitor
{
    private $addr = 0;
    private $names = [];

    private function newtemp()
    {
        $this->addr++;
        return $this->addr;
    }

    public function visitLine($ctx)
    {
        $statements = $ctx->statement();
        if ($statements !== null) {
            foreach ($statements as $stmt) {
                $this->visit($stmt);
            }
        }
        return null;
    }

    public function visitAssign($ctx)
    {
        $name = $ctx->NAME()->getText();
        $expr = $this->visit($ctx->expression());

        $this->names[$name] = $expr;

        echo $name . ' = ' . $expr . "\n";
        return null;
    }

    public function visitPrintExpr($ctx)
    {
        $this->visit($ctx->expression());
        return null;
    }

    public function visitAddSub($ctx)
    {
        $left = $this->visit($ctx->expression(0));
        $right = $this->visit($ctx->expression(1));
        $op = $ctx->op->getText();

        $this->newtemp();
        $temp = 't' . $this->addr;
        echo $temp . ' = ' . $left . ' ' . $op . ' ' . $right . "\n";

        return $temp;
    }

    public function visitMuldiv($ctx)
    {
        $left = $this->visit($ctx->expression(0));
        $right = $this->visit($ctx->expression(1));
        $op = $ctx->op->getText();


        $this->newtemp();
        $temp = 't' . $this->addr;

        echo $temp . ' = ' . $left . ' ' . $op . ' ' . $right . "\n";

        return $temp;
    }

    public function visitUminus($ctx)
    {
        $expr = $this->visit($ctx->expression());

        $this->newtemp();
        $temp = 't' . $this->addr;

        echo $temp . ' = minus ' . $expr . " \n";
        return $temp;
    }

    public function visitParens($ctx)
    {
        return $this->visit($ctx->expression());
    }

    public function visitNumber($ctx)
    {
        $num = trim($ctx->NUMBER()->getText());
        return (string)intval($num);
    }

    public function visitName($ctx)
    {
        $name = $ctx->NAME()->getText();

        if (array_key_exists($name, $this->names)) {
            //return $this->names[$name]
            return $name;
        } else {
            echo "Undefined name '" . $name . "'\n";
            return "0";
        }
    }
}
