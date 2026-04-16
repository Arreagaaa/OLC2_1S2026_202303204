<?php

// punto de entrada del backend: recibe codigo Golampi y retorna JSON con resultado

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// preflight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

require_once __DIR__ . '/../bootstrap.php';


use Antlr\Antlr4\Runtime\InputStream;
use Antlr\Antlr4\Runtime\CommonTokenStream;
use Antlr\Antlr4\Runtime\Error\Listeners\DiagnosticErrorListener;

use App\Interpreter\Visitor;
use App\Interpreter\CodeGen;
use App\Interpreter\ErrorHandler;
use App\Reports\SymbolTableReport;
use App\Reports\ErrorReport;
use App\Models\ErrorEntry;

$body = file_get_contents('php://input');
$data = json_decode($body, true);
$codigo = trim($data['codigo'] ?? '');

$output     = '';
$arm64Code  = '';
$errors     = [];
$symbols    = [];
$errorHandler = new ErrorHandler();

if ($codigo === '') {
    $codegen = new CodeGen('');

    echo json_encode([
        'output'      => '',
        'arm64_code'  => $codegen->generateProgram(),
        'errors'      => [],
        'symbols'     => [],
        'errorsHtml'  => '',
        'symbolsHtml' => '',
    ]);
    exit;
}

// listener personalizado para capturar errores sintacticos y lexicos del parser
class GolampiErrorListener extends \Antlr\Antlr4\Runtime\Error\Listeners\BaseErrorListener
{
    private ErrorHandler $handler;
    private string $errorType = 'Sintactico'; // por defecto

    public function __construct(ErrorHandler $handler, string $errorType = 'Sintactico')
    {
        $this->handler = $handler;
        $this->errorType = $errorType;
    }

    public function syntaxError(
        \Antlr\Antlr4\Runtime\Recognizer $recognizer,
        ?object $offendingSymbol,
        int $line,
        int $charPositionInLine,
        string $msg,
        ?\Antlr\Antlr4\Runtime\Error\Exceptions\RecognitionException $e
    ): void {
        // clasificar error: lexico si contiene "token recognition error", semantico si es otra cosa
        $type = $this->errorType;
        if (strpos($msg, 'token recognition error') !== false) {
            $type = 'Lexico';
        }
        $this->handler->addError($type, $msg, $line, $charPositionInLine);
    }
}

try {
    $inputStream = InputStream::fromString($codigo);

    $lexer  = new GolampiLexer($inputStream);
    $lexer->removeErrorListeners();
    // errores del lexer son clasificados como léxicos
    $lexer->addErrorListener(new GolampiErrorListener($errorHandler, 'Lexico'));

    $tokens = new CommonTokenStream($lexer);
    $parser = new GolampiParser($tokens);
    $parser->removeErrorListeners();
    // errores del parser son clasificados como sintácticos (si no son "token recognition error")
    $parser->addErrorListener(new GolampiErrorListener($errorHandler, 'Sintactico'));

    // regla de inicio
    $tree = $parser->program();

    if (!$errorHandler->hasErrors()) {
        $visitor = new Visitor();
        $output = $visitor->visit($tree);

        // combinar errores del visitor con los del parser
        foreach ($visitor->errors->getErrors() as $e) {
            $errorHandler->addSemantic($e->descripcion, $e->fila, $e->columna);
        }

        $symbols = $visitor->getSymbolTable();

        $codegen = new CodeGen($output);
        $arm64Code = $codegen->generateProgram();
    }
} catch (\Throwable $ex) {
    $errorHandler->addSemantic($ex->getMessage(), 0, 0);
}

$errorList = $errorHandler->getErrors();

// serializar errores a array simple para JSON
$errorsJson = array_map(fn($e) => [
    'tipo'        => $e->tipo,
    'descripcion' => $e->descripcion,
    'fila'        => $e->fila,
    'columna'     => $e->columna,
], $errorList);

// serializar simbolos a array simple para JSON
$symbolsJson = array_map(fn($s) => [
    'id'      => $s->id,
    'tipo'    => $s->tipo,
    'clase'   => $s->clase,
    'ambito'  => $s->ambito,
    'valor'   => is_bool($s->valor) ? ($s->valor ? 'true' : 'false')
        : (is_scalar($s->valor) ? (string) $s->valor
            : (is_array($s->valor) ? json_encode($s->valor) : $s->tipo)),
    'fila'    => $s->fila,
    'columna' => $s->columna,
], $symbols);

echo json_encode([
    'output'      => $output,
    'arm64_code'  => $arm64Code,
    'errors'      => $errorsJson,
    'symbols'     => $symbolsJson,
    'errorsHtml'  => ErrorReport::toHtml($errorList),
    'symbolsHtml' => SymbolTableReport::toHtml($symbols),
]);
