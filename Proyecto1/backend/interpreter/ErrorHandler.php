<?php

namespace App\Interpreter;

use App\Models\ErrorEntry;

// recolecta errores sin detener la ejecucion
class ErrorHandler
{
    private array $errors = [];

    // agrega un error semantico
    public function addSemantic(string $desc, int $fila, int $col): void
    {
        $this->errors[] = new ErrorEntry(ErrorEntry::SEMANTICO, $desc, $fila, $col);
    }

    // agrega un error lexico
    public function addLexic(string $desc, int $fila, int $col): void
    {
        $this->errors[] = new ErrorEntry(ErrorEntry::LEXICO, $desc, $fila, $col);
    }

    // agrega un error sintactico
    public function addSyntax(string $desc, int $fila, int $col): void
    {
        $this->errors[] = new ErrorEntry(ErrorEntry::SINTACTICO, $desc, $fila, $col);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }
}
