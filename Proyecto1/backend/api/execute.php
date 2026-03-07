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

use App\Interpreter\Interpreter;
use App\Interpreter\ErrorHandler;
use App\Reports\SymbolTableReport;
use App\Reports\ErrorReport;
use App\Models\ErrorEntry;

$body = file_get_contents('php://input');
$data = json_decode($body, true);
$codigo = trim($data['codigo'] ?? '');

if ($codigo === '') {
    echo json_encode(['output' => '', 'errors' => [], 'symbols' => [], 'errorsHtml' => '', 'symbolsHtml' => '']);
    exit;
}

$output     = '';
$errors     = [];
$symbols    = [];
$errorHandler = new ErrorHandler();

// listener personalizado para capturar errores sintacticos y lexicos del parser
class GolampiErrorListener extends \Antlr\Antlr4\Runtime\Error\Listeners\BaseErrorListener
{
    private ErrorHandler $handler;

    public function __construct(ErrorHandler $handler)
    {
        $this->handler = $handler;
    }

    public function syntaxError(
        \Antlr\Antlr4\Runtime\Recognizer $recognizer,
        ?object $offendingSymbol,
        int $line,
        int $charPositionInLine,
        string $msg,
        ?\Antlr\Antlr4\Runtime\Error\Exceptions\RecognitionException $e
    ): void {
        $this->handler->addSyntax($msg, $line, $charPositionInLine);
    }
}

try {
    $inputStream = InputStream::fromString($codigo);

    $lexer  = new GolampiLexer($inputStream);
    $lexer->removeErrorListeners();
    $lexer->addErrorListener(new GolampiErrorListener($errorHandler));

    $tokens = new CommonTokenStream($lexer);
    $parser = new GolampiParser($tokens);
    $parser->removeErrorListeners();
    $parser->addErrorListener(new GolampiErrorListener($errorHandler));

    // regla de inicio
    $tree = $parser->program();

    if (!$errorHandler->hasErrors()) {
        $interpreter = new Interpreter();
        $output = $interpreter->visit($tree);

        // combinar errores del interpreter con los del parser
        foreach ($interpreter->errors->getErrors() as $e) {
            $errorHandler->addSemantic($e->descripcion, $e->fila, $e->columna);
        }

        $symbols = $interpreter->getSymbolTable();
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
    'valor'   => is_scalar($s->valor) ? (string) $s->valor
                    : (is_array($s->valor) ? json_encode($s->valor) : $s->tipo),
    'fila'    => $s->fila,
    'columna' => $s->columna,
], $symbols);

echo json_encode([
    'output'      => $output,
    'errors'      => $errorsJson,
    'symbols'     => $symbolsJson,
    'errorsHtml'  => ErrorReport::toHtml($errorList),
    'symbolsHtml' => SymbolTableReport::toHtml($symbols),
]);
