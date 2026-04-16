<?php

namespace App\Arm64;

// emite instrucciones ARM64 con formato simple y legible
class ArmEmitter
{
    private array $lines = [];

    public function emit(string $line): void
    {
        $this->lines[] = $line;
    }

    public function emitComment(string $comment): void
    {
        $this->emit('# ' . $comment);
    }

    public function emitLabel(string $label): void
    {
        $this->lines[] = $label . ':';
    }

    public function emitSection(string $section): void
    {
        $this->emit('.section ' . $section);
    }

    public function emitGlobal(string $symbol): void
    {
        $this->emit('.global ' . $symbol);
    }

    public function emitAlign(int $alignment = 2): void
    {
        $this->emit('.align ' . $alignment);
    }

    public function emitAscii(string $label, string $text): void
    {
        $escaped = addcslashes($text, "\\\"\n\r\t\0");
        $this->emit($label . ': .ascii "' . $escaped . '"');
    }

    public function emitRaw(string $raw): void
    {
        $this->lines[] = $raw;
    }

    public function toString(): string
    {
        return implode("\n", $this->lines) . (empty($this->lines) ? '' : "\n");
    }
}