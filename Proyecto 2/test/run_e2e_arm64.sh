#!/usr/bin/env bash
set -euo pipefail

PROJECT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
INPUT_FILE="${1:-$PROJECT_DIR/test/test1/archivo1_basico.go}"

if [[ ! -f "$INPUT_FILE" ]]; then
  echo "ERROR: no existe el archivo de entrada: $INPUT_FILE"
  exit 1
fi

cd "$PROJECT_DIR"

INPUT_FILE="$INPUT_FILE" php <<'PHP'
<?php
$inputFile = getenv('INPUT_FILE');
$src = file_get_contents($inputFile);
require "backend/bootstrap.php";

$input = Antlr\Antlr4\Runtime\InputStream::fromString($src);
$lexer = new GolampiLexer($input);
$tokens = new Antlr\Antlr4\Runtime\CommonTokenStream($lexer);
$parser = new GolampiParser($tokens);
$tree = $parser->program();

$visitor = new App\Interpreter\Visitor();
$interp = (string) $visitor->visit($tree);

$cg = new App\Interpreter\CodeGen($src, $interp);
$asmCode = $cg->generateProgram();
file_put_contents("output/e2e_test.s", $asmCode);

$asm = new App\Arm64\Assembler();
$assemble = $asm->assemble("output/e2e_test.s", "output/e2e_test.o");
$link = $asm->link("output/e2e_test.o", "output/e2e_test.bin");
$run = $asm->runBinary("output/e2e_test.bin");

if (!$assemble["ok"] || !$link["ok"] || !$run["ok"]) {
    echo "STATUS=FAIL" . PHP_EOL;
    echo "ASSEMBLE_OK=" . ($assemble["ok"] ? "YES" : "NO") . PHP_EOL;
    echo "LINK_OK=" . ($link["ok"] ? "YES" : "NO") . PHP_EOL;
    echo "RUN_OK=" . ($run["ok"] ? "YES" : "NO") . PHP_EOL;
    echo "ASSEMBLE_OUT=" . ($assemble["output"] ?? "") . PHP_EOL;
    echo "LINK_OUT=" . ($link["output"] ?? "") . PHP_EOL;
    echo "RUN_OUT=" . ($run["output"] ?? "") . PHP_EOL;
    exit(2);
}

$qemuOut = (string) ($run["output"] ?? "");

$normalize = function (string $s): string {
    $s = str_replace(["\r\n", "\r"], "\n", $s);
    $s = preg_replace("/[ \t]+\n/", "\n", $s);
    return rtrim($s, "\n");
};

$same = ($normalize($interp) === $normalize($qemuOut));

echo "STATUS=" . ($same ? "PASS" : "FAIL") . PHP_EOL;
echo "FALLBACK=" . (str_contains($asmCode, "fallback ARM64") ? "YES" : "NO") . PHP_EOL;
echo "ASSEMBLE_OK=YES" . PHP_EOL;
echo "LINK_OK=YES" . PHP_EOL;
echo "RUN_OK=YES" . PHP_EOL;
echo "NORMALIZED_OUTPUT_MATCH=" . ($same ? "YES" : "NO") . PHP_EOL;
echo "INTERP_LINES=" . (substr_count(trim($interp), "\n") + 1) . PHP_EOL;
echo "QEMU_LINES=" . (substr_count(trim($qemuOut), "\n") + 1) . PHP_EOL;

if (!$same) {
    file_put_contents("/tmp/golampi_interp.out", $interp);
    file_put_contents("/tmp/golampi_qemu.out", $qemuOut);
}
PHP
