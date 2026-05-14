<?php

require_once __DIR__ . '/vendor/autoload.php';

require_once  __DIR__ . '/GrammarLexer.php';
require_once __DIR__ . '/GrammarParser.php';
require_once __DIR__ . '/GrammarVisitor.php';
require_once __DIR__ . '/GrammarBaseVisitor.php';

require_once __DIR__ . '/EvalVisitor.php';

use Antlr\Antlr4\Runtime\InputStream;
use Antlr\Antlr4\Runtime\CommonTokenStream;

$input = $argv[1] ?? null;

if (!$input) {
    echo "Uso: php index.php \"expression\"\n";
    exit(1);
}

$stream = InputStream::fromString($input);
$lexer  = new GrammarLexer($stream);
$tokens = new CommonTokenStream($lexer);
$parser = new GrammarParser($tokens);

$tree = $parser->l();

$visitor = new EvalVisitor();
$result  = $visitor->visit($tree);

echo $result . "\n";
