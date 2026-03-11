<?php

namespace App\Interpreter;

use App\Models\Symbol;

// entorno de ejecucion con soporte para scopes anidados via parent pointer
class Environment
{
    private ?Environment $father;
    // almacena Symbol por nombre
    private array $values = [];

    public function __construct(?Environment $father = null)
    {
        $this->father = $father;
    }

    // declara un nuevo simbolo en el scope actual
    public function declare(string $key, Symbol $symbol): void
    {
        $this->values[$key] = $symbol;
    }

    // asigna un valor a un simbolo ya declarado (busca en cadena de scopes)
    public function assign(string $key, mixed $value): void
    {
        if (array_key_exists($key, $this->values)) {
            $this->values[$key]->valor = $value;
            // si el símbolo es un alias byRef, propagar al original
            if (isset($this->values[$key]->refName)) {
                $this->values[$key]->refEnv->assign($this->values[$key]->refName, $value);
            }
            return;
        }
        if ($this->father !== null) {
            $this->father->assign($key, $value);
            return;
        }
        throw new \RuntimeException("Variable '$key' no declarada.");
    }

    // obtiene el simbolo de un identificador (busca en cadena de scopes)
    public function get(string $key): Symbol
    {
        if (array_key_exists($key, $this->values)) {
            return $this->values[$key];
        }
        if ($this->father !== null) {
            return $this->father->get($key);
        }
        throw new \RuntimeException("Variable '$key' no declarada.");
    }

    // devuelve referencia al valor para paso por referencia (punteros)
    public function &getRef(string $key): mixed
    {
        if (array_key_exists($key, $this->values)) {
            return $this->values[$key]->valor;
        }
        if ($this->father !== null) {
            return $this->father->getRef($key);
        }
        throw new \RuntimeException("Variable '$key' no declarada.");
    }

    // verifica si el identificador existe en el scope actual (sin subir)
    public function existsLocal(string $key): bool
    {
        return array_key_exists($key, $this->values);
    }

    // devuelve el símbolo del scope actual (sin subir), o null si no existe
    public function getLocal(string $key): ?Symbol
    {
        return $this->values[$key] ?? null;
    }

    // devuelve todos los simbolos declarados en este scope y descendientes
    public function getAllSymbols(): array
    {
        $symbols = array_values($this->values);
        // no se sube al padre para evitar duplicados en la tabla global
        return $symbols;
    }

    public function getFather(): ?Environment
    {
        return $this->father;
    }
}
