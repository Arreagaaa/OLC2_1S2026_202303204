<?php

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/GrammarLexer.php';
require_once __DIR__ . '/GrammarParser.php';
require_once __DIR__ . '/GrammarListener.php';
require_once __DIR__ . '/GrammarBaseListener.php';

require_once __DIR__ . '/ASTListener.php';
require_once __DIR__ . '/Tree.php';

use Antlr\Antlr4\Runtime\InputStream;
use Antlr\Antlr4\Runtime\CommonTokenStream;
use Antlr\Antlr4\Runtime\Tree\ParseTreeWalker;

$input = $argv[1] ?? null;

if (!$input) {
    echo "Uso: php index.php \"1+2*3\"\n";
    exit(1);
}

$stream = InputStream::fromString($input);
$lexer  = new GrammarLexer($stream);
$tokens = new CommonTokenStream($lexer);
$parser = new GrammarParser($tokens);

$tree = $parser->e();

$listener = new ASTListener();
$walker = new ParseTreeWalker();
$walker->walk($listener, $tree);

$root = $listener->getResult();
$astTree = new Tree();
$astTree->root = $root;

echo "Inorder: ";
$astTree->inOrder($root);
echo "\n";

echo "Postorder: ";
$astTree->postOrder($root);
echo "\n";

$astTree->dot .= "}";
echo "DOT:\n" . $astTree->dot . "\n";

