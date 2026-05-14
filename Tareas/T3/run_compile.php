<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';

use Antlr\Antlr4\Runtime\InputStream;
use Antlr\Antlr4\Runtime\CommonTokenStream;
use Antlr\Antlr4\Runtime\Error\BailErrorStrategy;

$code = stream_get_contents(STDIN);
if (trim($code) === '') {
    fwrite(STDERR, "Error: ningun codigo proporcionado\n");
    exit(1);
}

$inputStream = InputStream::fromString($code);
$lexer       = new GrammarLexer($inputStream);
$tokens      = new CommonTokenStream($lexer);
$parser      = new GrammarParser($tokens);
$parser->setErrorHandler(new BailErrorStrategy());
$tree = $parser->p();

$compiler = new Compiler();
$result   = $compiler->visit($tree);

echo $result->toString(), PHP_EOL;
