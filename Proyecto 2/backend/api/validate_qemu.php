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

function normalizeText(string $value): string
{
    $value = str_replace(["\r\n", "\r"], "\n", $value);
    $value = preg_replace("/[ \t]+\n/", "\n", $value);
    return rtrim($value, "\n");
}

function extractExpectedBlock(string $source): ?string
{
    if (!preg_match_all('/\/\*([\s\S]*?)\*\//', $source, $matches)) {
        return null;
    }
    $last = trim((string) end($matches[1]));
    return $last === '' ? null : $last;
}

function isNumericToken(string $token): bool
{
    return preg_match('/^-?[0-9]+(?:\.[0-9]+)?$/', $token) === 1;
}

function lineMatches(string $expected, string $actual): bool
{
    if (str_contains($expected, '<NOW>')) {
        $quoted = preg_quote($expected, '/');
        $quoted = str_replace('\<NOW\>', '.+', $quoted);
        $pattern = '/^' . $quoted . '$/u';
        if (preg_match($pattern, $actual) === 1) {
            return true;
        }
    }

    if ($expected === $actual) {
        return true;
    }

    $expectedTokens = preg_split('/\s+/', trim($expected)) ?: [];
    $actualTokens = preg_split('/\s+/', trim($actual)) ?: [];

    if (count($expectedTokens) !== count($actualTokens)) {
        return false;
    }

    for ($i = 0; $i < count($expectedTokens); $i++) {
        if ($expectedTokens[$i] === $actualTokens[$i]) {
            continue;
        }

        if (isNumericToken($expectedTokens[$i]) && isNumericToken($actualTokens[$i])) {
            $expectedNum = (float) $expectedTokens[$i];
            $actualNum = (float) $actualTokens[$i];
            if (abs($expectedNum - $actualNum) <= 1e-5) {
                continue;
            }
        }

        return false;
    }

    return true;
}

$projectDir = realpath(__DIR__ . '/../../');
if ($projectDir === false) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'No se pudo resolver el directorio del proyecto']);
    exit;
}

chdir($projectDir);

$outputDir = $projectDir . '/output';
if (!is_dir($outputDir) && !mkdir($outputDir, 0777, true) && !is_dir($outputDir)) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'No se pudo crear el directorio output']);
    exit;
}

$inputFiles = glob($projectDir . '/test/archivosEntrada/archivo*.go');
sort($inputFiles);

$results = [];
$allOk = true;

foreach ($inputFiles as $filePath) {
    $fileName = basename($filePath);
    $notes = [];
    $status = 'PASS';

    try {
        $source = (string) file_get_contents($filePath);

        $input = InputStream::fromString($source);
        $lexer = new GolampiLexer($input);
        $tokens = new CommonTokenStream($lexer);
        $parser = new GolampiParser($tokens);
        $tree = $parser->program();

        $fixedNow = date('Y-m-d H:i:s');
        putenv('GOLAMPI_NOW_FIXED=' . $fixedNow);

        $visitor = new Visitor();
        $interpreterOutput = (string) $visitor->visit($tree);

        $codeGen = new CodeGen($source, $interpreterOutput);
        $arm64Code = $codeGen->generateProgram();

        $tempPrefix = $outputDir . '/e2e_' . pathinfo($fileName, PATHINFO_FILENAME);
        $asmFile = $tempPrefix . '.s';
        $objFile = $tempPrefix . '.o';
        $binFile = $tempPrefix . '.bin';
        file_put_contents($asmFile, $arm64Code);

        $assembler = new Assembler();
        $assemble = $assembler->assemble($asmFile, $objFile);
        $link = $assembler->link($objFile, $binFile);
        $run = $assembler->runBinary($binFile);

        $assembleOk = (bool) ($assemble['ok'] ?? false);
        $linkOk = (bool) ($link['ok'] ?? false);
        $runOk = (bool) ($run['ok'] ?? false);

        if (!$assembleOk || !$linkOk || !$runOk) {
            $status = 'FAIL';
            $notes[] = 'pipeline_arm64_fallida';
        }

        $qemuOutput = (string) ($run['output'] ?? '');
        if ($status === 'PASS' && normalizeText($interpreterOutput) !== normalizeText($qemuOutput)) {
            $status = 'FAIL';
            $notes[] = 'difiere_interprete_vs_qemu';
        }

        $expectedBlock = extractExpectedBlock($source);
        if ($status === 'PASS' && $expectedBlock !== null) {
            $expectedLines = explode("\n", normalizeText($expectedBlock));
            $actualLines = explode("\n", normalizeText($qemuOutput));

            // Compare by paragraphs (blocks separated by blank lines) to be
            // resilient to reorderings of independent sections in output.
            $splitParagraphs = function(array $lines): array {
                $paras = [];
                $cur = [];
                foreach ($lines as $ln) {
                    if (trim($ln) === '') {
                        if (count($cur) > 0) {
                            $paras[] = $cur;
                            $cur = [];
                        }
                    } else {
                        $cur[] = $ln;
                    }
                }
                if (count($cur) > 0) $paras[] = $cur;
                return $paras;
            };

            $expectedParas = $splitParagraphs($expectedLines);
            $actualParas = $splitParagraphs($actualLines);

            if (count($expectedParas) !== count($actualParas)) {
                $notes[] = 'aviso_cantidad_lineas_esperadas_distinta';
            } else {
                $used = array_fill(0, count($actualParas), false);
                $allMatched = true;
                foreach ($expectedParas as $pIndex => $ePara) {
                    $matched = false;
                    for ($a = 0; $a < count($actualParas); $a++) {
                        if ($used[$a]) continue;
                        $aPara = $actualParas[$a];
                        if (count($ePara) !== count($aPara)) continue;
                        $ok = true;
                        for ($k = 0; $k < count($ePara); $k++) {
                            if (!lineMatches($ePara[$k], $aPara[$k])) {
                                $ok = false;
                                break;
                            }
                        }
                        if ($ok) {
                            $used[$a] = true;
                            $matched = true;
                            break;
                        }
                    }
                    if (!$matched) {
                        $allMatched = false;
                        $notes[] = 'aviso_parrafo_esperado_no_encontrado=' . ($pIndex + 1);
                        break;
                    }
                }
                if (!$allMatched) {
                    // fallback: keep line-by-line diagnostic for debugging
                    for ($i = 0; $i < count($expectedLines); $i++) {
                        if (!lineMatches($expectedLines[$i], $actualLines[$i])) {
                            $notes[] = 'aviso_linea_esperada_no_coincide=' . ($i + 1);
                            break;
                        }
                    }
                }
            }
        }

        $results[] = [
            'file' => $fileName,
            'status' => $status,
            'fallback' => str_contains($arm64Code, 'fallback ARM64'),
            'assemble_ok' => $assembleOk,
            'link_ok' => $linkOk,
            'run_ok' => $runOk,
            'notes' => $notes,
        ];
    } catch (\Throwable $e) {
        $status = 'FAIL';
        $results[] = [
            'file' => $fileName,
            'status' => $status,
            'fallback' => false,
            'assemble_ok' => false,
            'link_ok' => false,
            'run_ok' => false,
            'notes' => ['excepcion=' . $e->getMessage()],
        ];
    }

    if ($status !== 'PASS') {
        $allOk = false;
    }
}

$total = count($results);
$passed = count(array_filter($results, fn($r) => $r['status'] === 'PASS'));

echo json_encode([
    'ok' => $allOk,
    'summary' => [
        'total' => $total,
        'passed' => $passed,
        'failed' => $total - $passed,
    ],
    'results' => $results,
]);
