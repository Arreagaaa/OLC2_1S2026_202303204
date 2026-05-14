<?php

require __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/ANTLRv4/GrammarLexer.php';
require_once __DIR__ . '/ANTLRv4/GrammarParser.php';
require_once __DIR__ . '/ANTLRv4/GrammarVisitor.php';
require_once __DIR__ . '/ANTLRv4/GrammarBaseVisitor.php';
require_once __DIR__ . '/EvalVisitor.php';

use Antlr\Antlr4\Runtime\InputStream;
use Antlr\Antlr4\Runtime\CommonTokenStream;

$input = $argv[1] ?? null;

if (!$input) {
    echo "Usage : php index.php \"code\"\n";
    exit(1);
}

$stream = InputStream::fromString($input);
$lexer = new GrammarLexer($stream);
$tokens = new CommonTokenStream($lexer);
$parser = new GrammarParser($tokens);

$tree = $parser->line();

$visitor = new EvalVisitor();
$result = $visitor->visit($tree);

echo $result . "\n";
