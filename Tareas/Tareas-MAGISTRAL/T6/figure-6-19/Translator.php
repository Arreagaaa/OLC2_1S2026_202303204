<?php

use Context\ProgramContext;
use Context\AssignContext;
use Context\SumresContext;
use Context\MulDivContext;
use Context\PassEContext;
use Context\IdContext;
use Context\NumContext;

class Attr
{
    public ?string $AddrNUM = null;
    public ?string $AddrID = null;
    public ?string $Code = null;
}

class SymbolTable
{
    public array $IDS = [];

    public function insert(string $name): void
    {
        $this->IDS[$name] = true;
    }

    public function exists(string $name): bool
    {
        return isset($this->IDS[$name]);
    }
}

class Translator extends GrammarBaseVisitor
{
    public int $TID = 0;
    public string $Data = ".global _start\n\n.data\n";
    public string $Code = "\n.text\n_start:\n";

    private SymbolTable $symbolTable;

    public function __construct()
    {
        $this->symbolTable = new SymbolTable();
    }

    public function isNUM(Attr $attr): bool
    {
        return $attr->AddrNUM !== null && $attr->AddrID === null;
    }

    public function visitProgram(ProgramContext $context)
    {
        foreach ($context->s() as $sContext) {
            $this->visit($sContext);
        }
        $this->Code .= "\tmov X8, #93\n\tsvc #0\n";
        $attr = new Attr();
        $attr->Code = $this->Data . $this->Code;
        return $attr;
    }

    public function visitAssign(AssignContext $context)
    {
        $id = $context->ID()->getText();
        $expr = $this->visit($context->e());

        if ($this->isNUM($expr)) {
            if ($this->symbolTable->exists($id)) {
                $this->Code .= "\tldr x0, =" . $id . "\n";
                $this->Code .= "\tmov x1, #" . $expr->AddrNUM . "\n";
                $this->Code .= "\tstr x1, [x0]\n\n";
            } else {
                $this->Data .= $id . ": .word " . $expr->AddrNUM . "\n";
                $this->symbolTable->insert($id);
            }
        } else {
            if ($this->symbolTable->exists($id)) {
                $this->Code .= "\tldr x0, =" . $id . "\n";
                $this->Code .= "\tldr x1, =" . $expr->AddrID . "\n";
                $this->Code .= "\tldr x2, [x1]\n";
                $this->Code .= "\tstr x2, [x0]\n\n";
            } else {
                $this->Data .= $id . ": .word 0\n";

                $this->Code .= "\tldr x0, =" . $id . "\n";
                $this->Code .= "\tldr x1, =" . $expr->AddrID . "\n";
                $this->Code .= "\tldr x2, [x1]\n";
                $this->Code .= "\tstr x2, [x0]\n";
                $this->symbolTable->insert($id);
            }
        }

        return new Attr();
    }

    public function visitSumres(\Context\SumresContext $context)
    {

        $left = $this->visit($context->e());
        $right = $this->visit($context->t());

        $this->TID++;
        $addr = "T" . $this->TID;
        $this->Data .= $addr . ": .word 0\n";

        $this->Code .= "\tldr x0, =" . $addr . "\n";

        $opText = $context->op->getText();
        $op = ($opText === "+") ? "add" : "sub";

        if ($this->isNUM($left)) {

            $this->Code .= "\tldr x1, [x0]\n";

            if ($this->isNUM($right)) {
                $this->Code .= "\tadd x1, x1, #" . $left->AddrNUM . "\n";
                $this->Code .= "\t" . $op . " x1, x1, #" . $right->AddrNUM . "\n";
                $this->Code .= "\tstr x1, [x0]\n";
            } else {
                $this->Code .= "\tadd x1, x1, #" . $left->AddrNUM . "\n";
                $this->Code .= "\tldr x2, =" . $right->AddrID . "\n";
                $this->Code .= "\tldr x3, [x2]\n";
                $this->Code .= "\t" . $op . " x1, x1, x3\n";
                $this->Code .= "\tstr x1, [x0]\n";
            }
        } else {

            if ($this->isNUM($right)) {
                $this->Code .= "\tldr x1, =" . $left->AddrID . "\n";
                $this->Code .= "\tldr x2, [x1]\n";
                $this->Code .= "\t" . $op . " x2, x2, #" . $right->AddrNUM . "\n";
                $this->Code .= "\tstr x2, [x0]\n";
            } else {
                $this->Code .= "\tldr x1, =" . $left->AddrID . "\n";
                $this->Code .= "\tldr x2, [x1]\n";
                $this->Code .= "\tldr x3, =" . $right->AddrID . "\n";
                $this->Code .= "\tldr x4, [x3]\n";
                $this->Code .= "\t" . $op . " x2, x2, x4\n";
                $this->Code .= "\tstr x2, [x0]\n";
            }
        }

        $this->Code .= "\n";

        $attr = new Attr();
        $attr->AddrID = $addr;

        return $attr;
    }

    public function visitMulDiv(MulDivContext $context)
    {
        $left = $this->visit($context->t());
        $right = $this->visit($context->f());

        $this->TID++;
        $addr = "T" . $this->TID;
        $this->Data .= $addr . ": .word 0\n";

        $this->Code .= "\tldr x0, =" . $addr . "\n";

        $opText = $context->op->getText();
        $op = ($opText === "*") ? "mul" : "sdiv";

        if ($this->isNUM($left)) {

            if ($this->isNUM($right)) {
                // NUM op NUM — mul/sdiv need registers, use mov for both
                $this->Code .= "\tmov x1, #" . $left->AddrNUM . "\n";
                $this->Code .= "\tmov x2, #" . $right->AddrNUM . "\n";
                $this->Code .= "\t" . $op . " x1, x1, x2\n";
                $this->Code .= "\tstr x1, [x0]\n";
            } else {
                // NUM op ID — load immediate via mov, then use register
                $this->Code .= "\tmov x1, #" . $left->AddrNUM . "\n";
                $this->Code .= "\tldr x2, =" . $right->AddrID . "\n";
                $this->Code .= "\tldr x3, [x2]\n";
                $this->Code .= "\t" . $op . " x1, x1, x3\n";
                $this->Code .= "\tstr x1, [x0]\n";
            }
        } else {

            if ($this->isNUM($right)) {
                // ID op NUM — load immediate via mov, then use register
                $this->Code .= "\tldr x1, =" . $left->AddrID . "\n";
                $this->Code .= "\tldr x2, [x1]\n";
                $this->Code .= "\tmov x3, #" . $right->AddrNUM . "\n";
                $this->Code .= "\t" . $op . " x2, x2, x3\n";
                $this->Code .= "\tstr x2, [x0]\n";
            } else {
                $this->Code .= "\tldr x1, =" . $left->AddrID . "\n";
                $this->Code .= "\tldr x2, [x1]\n";
                $this->Code .= "\tldr x3, =" . $right->AddrID . "\n";
                $this->Code .= "\tldr x4, [x3]\n";
                $this->Code .= "\t" . $op . " x2, x2, x4\n";
                $this->Code .= "\tstr x2, [x0]\n";
            }
        }

        $this->Code .= "\n";

        $attr = new Attr();
        $attr->AddrID = $addr;

        return $attr;
    }

    public function visitPassE(\Context\PassEContext $context)
    {
        return $this->visit($context->e());
    }

    public function visitId(\Context\IdContext $context)
    {
        $id = $context->ID()->getText();
        $attr = new Attr();
        $attr->AddrID = $id;
        return $attr;
    }

    public function visitNum(\Context\NumContext $context)
    {
        $num = $context->NUM()->getText();
        $attr = new Attr();
        $attr->AddrNUM = $num;
        return $attr;
    }
}
