<?php

namespace App\Interpreter;

use App\Arm64\ArmEmitter;
use App\Arm64\Assembler;

// generador inicial de ARM64: emite un programa que imprime la salida calculada
class CodeGen
{
    private string $console;
    private ArmEmitter $emitter;
    private Assembler $assembler;

    public function __construct(string $console = '')
    {
        $this->console   = $console;
        $this->emitter   = new ArmEmitter();
        $this->assembler = new Assembler();
    }

    public function generateProgram(): string
    {
        if ($this->console !== '') {
            $this->emitter->emitComment('codigo ARM64 generado para Proyecto 2');
            $this->emitter->emitSection('.data');
            $this->emitter->emitAscii('golampi_output', $this->console);
            $this->emitter->emitRaw('golampi_output_len = . - golampi_output');
        } else {
            $this->emitter->emitComment('programa vacio generado para la fase inicial');
        }

        $this->emitter->emitSection('.text');
        $this->emitter->emitAlign(2);
        $this->emitter->emitGlobal('_start');
        $this->emitter->emitLabel('_start');

        if ($this->console !== '') {
            $this->emitter->emitComment('write(stdout, golampi_output, len)');
            $this->emitter->emit('adrp x1, golampi_output');
            $this->emitter->emit('add x1, x1, :lo12:golampi_output');
            $this->emitter->emit('mov x0, #1');
            $this->emitter->emit('ldr x2, =golampi_output_len');
            $this->emitter->emit('mov x8, #64');
            $this->emitter->emit('svc #0');
        }

        $this->emitter->emitComment('exit(0)');
        $this->emitter->emit('mov x0, #0');
        $this->emitter->emit('mov x8, #93');
        $this->emitter->emit('svc #0');

        return $this->emitter->toString();
    }

    public function toolchainReady(): bool
    {
        return $this->assembler->hasToolchain();
    }
}