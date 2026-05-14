<?php

// adjust path: autoload is in the same directory as index.php
require __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/GrammarLexer.php';
require_once __DIR__ . '/GrammarParser.php';
require_once __DIR__ . '/GrammarListener.php';
require_once __DIR__ . '/GrammarBaseListener.php';
require_once __DIR__ . '/ASTListener.php';
require_once __DIR__ . '/Tree.php';

use Antlr\Antlr4\Runtime\InputStream;
use Antlr\Antlr4\Runtime\CommonTokenStream;
use Antlr\Antlr4\Runtime\Tree\ParseTreeWalker;

$input =  $argv[1] ?? null;

if (!$input) {
    echo "Usage: php index.php \"1+2*3\"\n";
    exit(1);
}

$stream = InputStream::fromString($input);
$lexer = new GrammarLexer($stream);
$tokens = new CommonTokenStream($lexer);
$parser = new GrammarParser($tokens);

$tree = $parser->e();

$listener = new ASTListener();
ParseTreeWalker::default()->walk($listener, $tree);

$root = $listener->getResult();

$astTree = new Tree();
$astTree->root = $root;

echo "InOrder: ";
$astTree->inOrder($root);
echo "\n";

echo "PostOrder: ";
$astTree->postOrder($root);
echo "\n";

$astTree->dot .= "}";
echo "DOT:\n" . $astTree->dot . "\n";

$outputPng = "output.png";

$cmd = "echo " . escapeshellarg($astTree->dot) .
    " | dot -Tpng -o " . escapeshellarg($outputPng);

exec($cmd, $output, $returVar);

if ($returVar == 0) {
    echo "PNG generated successfully\n";
} else {
    echo "Error generating PNG\n";
}
