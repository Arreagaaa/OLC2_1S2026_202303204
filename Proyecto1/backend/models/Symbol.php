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

    public function __construct(
        string $id,
        string $tipo,
        mixed  $valor,
        string $clase,
        string $ambito,
        int    $fila,
        int    $columna
    ) {
        $this->id      = $id;
        $this->tipo    = $tipo;
        $this->valor   = $valor;
        $this->clase   = $clase;
        $this->ambito  = $ambito;
        $this->fila    = $fila;
        $this->columna = $columna;
    }
}
