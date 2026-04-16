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

    // agrega un error con tipo especifico (lexico, sintactico o semantico)
    public function addError(string $tipo, string $desc, int $fila, int $col): void
    {
        $tipoMap = [
            'Lexico' => ErrorEntry::LEXICO,
            'Sintactico' => ErrorEntry::SINTACTICO,
            'Semantico' => ErrorEntry::SEMANTICO,
        ];
        $constantTipo = $tipoMap[$tipo] ?? ErrorEntry::SINTACTICO;
        $this->errors[] = new ErrorEntry($constantTipo, $desc, $fila, $col);
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
