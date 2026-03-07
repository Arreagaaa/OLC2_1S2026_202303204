<?php

namespace App\Interpreter;

use App\Models\Symbol;

// representa una funcion definida por el usuario con closure al entorno de definicion
class FuncionUsuario extends Invocable
{
    // contexto del nodo FunctionDeclaration del CST
    public mixed   $ctx;
    // entorno capturado en el momento de la declaracion (closure)
    public Environment $closure;
    // lista de ['name' => string, 'type' => string, 'byRef' => bool]
    public array   $params;
    // lista de tipos de retorno (puede haber multiples)
    public array   $returnTypes;

    public function __construct(
        mixed       $ctx,
        Environment $closure,
        array       $params,
        array       $returnTypes = []
    ) {
        $this->ctx         = $ctx;
        $this->closure     = $closure;
        $this->params      = $params;
        $this->returnTypes = $returnTypes;
    }

    public function getArity(): int
    {
        return count($this->params);
    }

    public function invoke(Interpreter $visitor, array $args): mixed
    {
        $newEnv = new Environment($this->closure);

        foreach ($this->params as $i => $param) {
            $sym = new Symbol(
                $param['name'],
                $param['type'],
                $args[$i] ?? null,
                Symbol::CLASE_VARIABLE,
                'local',
                0,
                0
            );
            $newEnv->declare($param['name'], $sym);
        }

        $prevEnv      = $visitor->env;
        $visitor->env = $newEnv;

        $result = $visitor->visit($this->ctx->block());

        $visitor->env = $prevEnv;

        if ($result instanceof ReturnType) {
            return $result->value;
        }
        return null;
    }
}
