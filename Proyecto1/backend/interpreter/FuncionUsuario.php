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
            $arg = $args[$i] ?? null;

            if ($param['byRef']) {
                // el argumento es un array ['__ref__' => name, '__env__' => env]
                // creamos un símbolo especial que apunta al original
                if (is_array($arg) && isset($arg['__ref__'])) {
                    $refName = $arg['__ref__'];
                    $refEnv  = $arg['__env__'];
                    // guardamos el valor actual para declarar el símbolo
                    $refSym = $refEnv->get($refName);
                    $sym = new Symbol(
                        $param['name'],
                        $param['type'],
                        $refSym->valor,
                        Symbol::CLASE_VARIABLE,
                        'local',
                        0, 0
                    );
                    // marcamos la referencia en el símbolo para que assign() la propague
                    $sym->refName = $refName;
                    $sym->refEnv  = $refEnv;
                    $newEnv->declare($param['name'], $sym);
                    continue;
                }
            }

            $sym = new Symbol(
                $param['name'],
                $param['type'],
                $arg,
                Symbol::CLASE_VARIABLE,
                'local',
                0, 0
            );
            $newEnv->declare($param['name'], $sym);
        }

        $prevEnv      = $visitor->env;
        $visitor->env = $newEnv;

        $result = $visitor->visit($this->ctx->block());

        // propagar cambios de parámetros byRef al entorno original
        foreach ($this->params as $param) {
            if ($param['byRef']) {
                $localSym = $newEnv->getLocal($param['name']);
                if ($localSym !== null && isset($localSym->refName)) {
                    $localSym->refEnv->assign($localSym->refName, $localSym->valor);
                }
            }
        }

        $visitor->env = $prevEnv;

        if ($result instanceof ReturnType) {
            $val = $result->value;
            // múltiple retorno: envolver en ['__multi__' => [...]]
            if (is_array($val) && count($this->returnTypes) > 1) {
                return ['__multi__' => $val];
            }
            return $val;
        }
        return null;
    }
}
