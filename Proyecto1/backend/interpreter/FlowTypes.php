<?php

namespace App\Interpreter;

// tipos de control de flujo usados como valores de retorno del visitor
// siguen el mismo patron de los ejemplos de clase

class FlowType {}

class BreakType extends FlowType {}

class ContinueType extends FlowType {}

// transporta el valor de retorno de una funcion
class ReturnType extends FlowType
{
    public mixed $value;

    public function __construct(mixed $value)
    {
        $this->value = $value;
    }
}
