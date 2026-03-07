<?php

namespace App\Models;

// representa un error lexico, sintactico o semantico capturado durante la ejecucion
class ErrorEntry
{
    const LEXICO    = 'Lexico';
    const SINTACTICO = 'Sintactico';
    const SEMANTICO = 'Semantico';

    public string $tipo;
    public string $descripcion;
    public int    $fila;
    public int    $columna;

    public function __construct(
        string $tipo,
        string $descripcion,
        int    $fila,
        int    $columna
    ) {
        $this->tipo        = $tipo;
        $this->descripcion = $descripcion;
        $this->fila        = $fila;
        $this->columna     = $columna;
    }
}
