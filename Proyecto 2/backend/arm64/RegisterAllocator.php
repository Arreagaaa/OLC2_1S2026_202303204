<?php

namespace App\Arm64;

// asignador muy basico de registros temporales para la etapa inicial
class RegisterAllocator
{
    private array $free = ['x9', 'x10', 'x11', 'x12', 'x13', 'x14', 'x15'];
    private array $used = [];

    public function allocate(): ?string
    {
        $reg = array_shift($this->free);
        if ($reg !== null) {
            $this->used[] = $reg;
        }
        return $reg;
    }

    public function release(string $register): void
    {
        $index = array_search($register, $this->used, true);
        if ($index !== false) {
            unset($this->used[$index]);
            $this->free[] = $register;
        }
    }

    public function reset(): void
    {
        $this->free = ['x9', 'x10', 'x11', 'x12', 'x13', 'x14', 'x15'];
        $this->used = [];
    }
}