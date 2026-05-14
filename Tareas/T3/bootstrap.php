<?php
// Cargar autoload si existen dependencias instaladas (ANTLR runtime)
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
	require_once __DIR__ . '/vendor/autoload.php';
}

require_once __DIR__ . "/ANTLRv4/GrammarLexer.php";
require_once __DIR__ . "/ANTLRv4/GrammarParser.php";
require_once __DIR__ . "/ANTLRv4/GrammarVisitor.php";
require_once __DIR__ . "/ANTLRv4/GrammarBaseVisitor.php";
require_once __DIR__ . "/src/C3DGenerator.php";
require_once __DIR__ . "/src/Compiler.php";
