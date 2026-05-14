<?php

require __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/GrammarLexer.php';
require_once __DIR__ . '/GrammarParser.php';
require_once __DIR__ . '/GrammarVisitor.php';
require_once __DIR__ . '/GrammarBaseVisitor.php';
require_once __DIR__ . '/EvalVisitor.php';

use Antlr\Antlr4\Runtime\InputStream;
use Antlr\Antlr4\Runtime\CommonTokenStream;

$input = file_get_contents($argv[1]);

$stream = InputStream::fromString($input);
$lexer = new GrammarLexer($stream);
$tokens = new CommonTokenStream($lexer);
$parser = new GrammarParser($tokens);

$tree = $parser->program();

$visitor = new EvalVisitor();
fwrite(STDERR, "=== INTERPRETER ===\n");
$result = $visitor->visit($tree);
fwrite(STDERR, "\n\n=== COMPILER ===\nCode written to output.txt\n");
echo $result;
