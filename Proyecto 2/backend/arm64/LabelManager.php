<?php

namespace App\Arm64;

// genera etiquetas unicas para el codigo ARM64
class LabelManager
{
    private int $counter = 0;

    public function next(string $prefix = 'L'): string
    {
        return $prefix . '_' . $this->counter++;
    }
}