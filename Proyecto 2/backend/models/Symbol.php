<?php

namespace App\Models;

// representa una entrada en la tabla de simbolos
class Symbol
{
    const CLASE_VARIABLE  = 'variable';
    const CLASE_CONSTANTE = 'constante';
    const CLASE_FUNCION   = 'funcion';

    public string  $id;
    public string  $tipo;
    public mixed   $valor;
    public string  $clase;
    public string  $ambito;
    public int     $fila;
    public int     $columna;
    public int     $offset;
    // para paso por referencia: nombre de la variable original y su entorno
    public ?string $refName = null;
    public mixed   $refEnv  = null;

    public function __construct(
        string $id,
        string $tipo,
        mixed  $valor,
        string $clase,
        string $ambito,
        int    $fila,
        int    $columna,
        int    $offset = 0
    ) {
        $this->id      = $id;
        $this->tipo    = $tipo;
        $this->valor   = $valor;
        $this->clase   = $clase;
        $this->ambito  = $ambito;
        $this->fila    = $fila;
        $this->columna = $columna;
        $this->offset  = $offset;
    }
}
