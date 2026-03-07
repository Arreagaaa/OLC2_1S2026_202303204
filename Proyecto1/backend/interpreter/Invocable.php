<?php

namespace App\Interpreter;

// contrato para cualquier cosa invocable: funciones usuario y builtins
abstract class Invocable
{
    abstract public function getArity(): int;
    abstract public function invoke(Interpreter $visitor, array $args): mixed;
}
