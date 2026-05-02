<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method Not Allowed']);
    exit;
}

set_time_limit(0);

require_once __DIR__ . '/../bootstrap.php';

use Antlr\Antlr4\Runtime\InputStream;
use Antlr\Antlr4\Runtime\CommonTokenStream;
use App\Interpreter\Visitor;
use App\Interpreter\CodeGen;
use App\Arm64\Assembler;

$body = file_get_contents('php://input');
$data = json_decode($body, true);
$fileName = trim($data['file'] ?? '');

if (!$fileName) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Archivo no especificado']);
    exit;
}

$projectDir = realpath(__DIR__ . '/../../');
if ($projectDir === false) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'No se pudo resolver directorio del proyecto']);
    exit;
}

chdir($projectDir);

$filePath = $projectDir . '/test/archivosEntrada/' . basename($fileName);
if (!file_exists($filePath)) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'error' => 'Archivo no encontrado']);
    exit;
}

$outputDir = $projectDir . '/output';
if (!is_dir($outputDir) && !mkdir($outputDir, 0777, true) && !is_dir($outputDir)) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'No se pudo crear output dir']);
    exit;
}

$result = [
    'file' => $fileName,
    'status' => 'PASS',
    'steps' => [],
    'interpreter_output' => '',
    'qemu_output' => '',
    'symbols_count' => 0,
    'fallback' => false,
];

try {
    $source = (string) file_get_contents($filePath);

    // Paso 1: Parse
    $input = InputStream::fromString($source);
    $lexer = new GolampiLexer($input);
    $tokens = new CommonTokenStream($lexer);
    $parser = new GolampiParser($tokens);
    $tree = $parser->program();
    $result['steps'][] = ['step' => 'Parse', 'ok' => true];

    // Paso 2: Interpret
    $visitor = new Visitor();
    $interpreterOutput = (string) $visitor->visit($tree);
    $result['interpreter_output'] = $interpreterOutput;
    $result['symbols_count'] = count($visitor->getSymbolTable());
    $result['steps'][] = ['step' => 'Interpreter', 'ok' => true, 'symbols' => $result['symbols_count']];

    // Paso 3: CodeGen
    $codeGen = new CodeGen($source, $interpreterOutput);
    $arm64Code = $codeGen->generateProgram();
    $result['fallback'] = str_contains($arm64Code, 'fallback ARM64');
    $result['steps'][] = ['step' => 'CodeGen', 'ok' => true, 'fallback' => $result['fallback']];

    // Paso 4: Assemble, Link, Run
    $tempPrefix = $outputDir . '/e2e_' . pathinfo($fileName, PATHINFO_FILENAME);
    $asmFile = $tempPrefix . '.s';
    $objFile = $tempPrefix . '.o';
    $binFile = $tempPrefix . '.bin';
    file_put_contents($asmFile, $arm64Code);

    $assembler = new Assembler();
    $assemble = $assembler->assemble($asmFile, $objFile);
    if (!($assemble['ok'] ?? false)) {
        $result['status'] = 'FAIL';
        $result['steps'][] = ['step' => 'Assemble', 'ok' => false, 'error' => $assemble['output'] ?? ''];
        echo json_encode($result);
        exit;
    }
    $result['steps'][] = ['step' => 'Assemble', 'ok' => true];

    $link = $assembler->link($objFile, $binFile);
    if (!($link['ok'] ?? false)) {
        $result['status'] = 'FAIL';
        $result['steps'][] = ['step' => 'Link', 'ok' => false, 'error' => $link['output'] ?? ''];
        echo json_encode($result);
        exit;
    }
    $result['steps'][] = ['step' => 'Link', 'ok' => true];

    $run = $assembler->runBinary($binFile);
    if (!($run['ok'] ?? false)) {
        $result['status'] = 'FAIL';
        $result['steps'][] = ['step' => 'Run QEMU', 'ok' => false, 'error' => $run['output'] ?? ''];
        echo json_encode($result);
        exit;
    }
    $result['qemu_output'] = (string) ($run['output'] ?? '');
    $result['steps'][] = ['step' => 'Run QEMU', 'ok' => true];

    // Paso 5: Comparar salidas
    $normalize = function(string $s): string {
        $s = str_replace(["\r\n", "\r"], "\n", $s);
        $s = preg_replace("/[ \t]+\n/", "\n", $s);
        return rtrim($s, "\n");
    };

    $interpNorm = $normalize($interpreterOutput);
    $qemuNorm = $normalize($result['qemu_output']);

    if ($interpNorm !== $qemuNorm) {
        $result['status'] = 'FAIL';
        $result['steps'][] = ['step' => 'Compare Outputs', 'ok' => false, 'error' => 'Interpreter vs QEMU differ'];
    } else {
        $result['steps'][] = ['step' => 'Compare Outputs', 'ok' => true, 'match' => 'Interpreter === QEMU'];
    }
} catch (\Throwable $e) {
    $result['status'] = 'FAIL';
    $result['steps'][] = ['step' => 'Exception', 'ok' => false, 'error' => $e->getMessage()];
}

echo json_encode($result);
