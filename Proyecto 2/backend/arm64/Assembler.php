<?php

namespace App\Arm64;

// utilidades para verificar la toolchain ARM64 del servidor
class Assembler
{
    public function hasCommand(string $command): bool
    {
        $result = trim((string) shell_exec('command -v ' . escapeshellarg($command) . ' 2>/dev/null'));
        return $result !== '';
    }

    public function hasToolchain(): bool
    {
        return $this->hasCommand('aarch64-linux-gnu-as')
            && $this->hasCommand('aarch64-linux-gnu-ld')
            && $this->hasCommand('qemu-aarch64');
    }

    public function assemble(string $sourcePath, string $objectPath): array
    {
        if (!$this->hasCommand('aarch64-linux-gnu-as')) {
            return ['ok' => false, 'output' => 'aarch64-linux-gnu-as no encontrado.'];
        }

        return $this->runCommand(
            'aarch64-linux-gnu-as ' . escapeshellarg($sourcePath) . ' -o ' . escapeshellarg($objectPath)
        );
    }

    public function link(string $objectPath, string $binaryPath): array
    {
        if (!$this->hasCommand('aarch64-linux-gnu-ld')) {
            return ['ok' => false, 'output' => 'aarch64-linux-gnu-ld no encontrado.'];
        }

        return $this->runCommand(
            'aarch64-linux-gnu-ld ' . escapeshellarg($objectPath) . ' -o ' . escapeshellarg($binaryPath)
        );
    }

    public function runBinary(string $binaryPath): array
    {
        if (!$this->hasCommand('qemu-aarch64')) {
            return ['ok' => false, 'output' => 'qemu-aarch64 no encontrado.'];
        }

        return $this->runCommand('qemu-aarch64 ' . escapeshellarg($binaryPath));
    }

    public function status(): array
    {
        return [
            'as' => $this->hasCommand('aarch64-linux-gnu-as'),
            'ld' => $this->hasCommand('aarch64-linux-gnu-ld'),
            'qemu' => $this->hasCommand('qemu-aarch64'),
        ];
    }

    private function runCommand(string $command): array
    {
        $output = [];
        $code = 0;
        exec($command . ' 2>&1', $output, $code);

        return [
            'ok' => $code === 0,
            'exitCode' => $code,
            'output' => trim(implode("\n", $output)),
        ];
    }
}