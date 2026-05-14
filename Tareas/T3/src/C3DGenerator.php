<?php

// C3DGenerator.php: Genera código de tres direcciones (C3D) a partir del AST.
// Proporciona métodos para crear temporales, emitir instrucciones de asignación,
// operaciones aritméticas/lógicas, saltos condicionales y etiquetas.

class C3DGenerator
{
    private $instructions = [];
    private $tempCount    = 0;
    private $labelCount   = 0;

    // ── Temporales ────────────────────────────
    public function newTemp(): string
    {
        $this->tempCount++;
        return "t" . $this->tempCount;
    }

    // ── Etiquetas ─────────────────────────────
    public function newLabel(): string
    {
        $this->labelCount++;
        return "L" . $this->labelCount;
    }

    public function emitLabel(string $label): void
    {
        $this->instructions[] = "$label:";
    }

    // ── Instrucciones básicas ─────────────────
    public function emit(string $result, string $left, string $op, string $right): void
    {
        $this->instructions[] = "$result = $left $op $right";
    }

    public function emitCopy(string $result, string $value): void
    {
        $this->instructions[] = "$result = $value";
    }

    // ── Print ─────────────────────────────────
    public function emitPrint(string $value): void
    {
        // El playground C3D solo maneja valores numericos. Si llega un string,
        // convertir etiquetas booleanas comunes a 1/0 para evitar errores.
        if (preg_match('/^(".*"|\'.*\')$/s', $value)) {
            $text = substr($value, 1, -1);
            $normalized = strtolower(trim($text));

            if ($normalized === 'verdadero' || $normalized === 'true') {
                $this->instructions[] = 'print 1';
                return;
            }

            if ($normalized === 'falso' || $normalized === 'false') {
                $this->instructions[] = 'print 0';
                return;
            }

            // Fallback seguro para strings no soportados por el playground.
            $this->instructions[] = 'print 0';
            return;
        }

        $this->instructions[] = "print $value";
    }

    // ── Saltos ────────────────────────────────

    /** if cond goto label  (salta si TRUE) */
    public function emitIfTrue(string $cond, string $label): void
    {
        $this->instructions[] = "if $cond goto $label";
    }

    /** if !cond goto label (salta si FALSE) */
    public function emitIfFalse(string $cond, string $label): void
    {
        $this->instructions[] = "if !$cond goto $label";
    }

    public function emitGoto(string $label): void
    {
        $this->instructions[] = "goto $label";
    }

    // ── Salida ────────────────────────────────
    public function toString(): string
    {
        return implode("\n", $this->instructions);
    }
}
