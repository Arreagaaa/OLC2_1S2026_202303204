<?php

// C3DGenerator.php: Sirve para generar código de tres direcciones (C3D) a partir del AST. 
// Proporciona métodos para crear temporales, emitir instrucciones de asignación y operaciones,
// y generar código de impresión. (Es decir, captura las líneas de código intermedio)

class C3DGenerator
{
    private $instructions = [];
    private $tempCount = 0;

    // Creacion de un nuevo temporal
    public function newTemp()
    {
        $this->tempCount++;
        return "t" . $this->tempCount;
    }

    public function emit($result, $left, $op, $right)
    {
        $this->instructions[] = "$result = $left $op $right";
    }

    public function emitCopy($result, $value)
    {
        $this->instructions[] = "$result = $value";
    }

    // Emite: print x
    public function emitPrint($value)
    {
        $this->instructions[] = "print $value";
    }

    // Para if-else: etiquetas y saltos
    public function newLabel()
    {
        static $count = 0;
        $count++;
        return "L" . $count;
    }

    public function emitLabel($label)
    {
        $this->instructions[] = "$label:";
    }

    // if !cond goto label
    public function emitIfFalse($cond, $label)
    {
        $this->instructions[] = "if !$cond goto $label";
    }

    public function emitGoto($label)
    {
        $this->instructions[] = "goto $label";
    }

    public function toString()
    {
        return implode("\n", $this->instructions);
    }
}
