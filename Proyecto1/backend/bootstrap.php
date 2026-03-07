<?php

// carga el autoloader de composer (vendor esta en la raiz del proyecto)
require_once __DIR__ . '/../vendor/autoload.php';

// archivos generados por ANTLR4 (no administrados por composer autoload directamente)
require_once __DIR__ . '/generated/GolampiLexer.php';
require_once __DIR__ . '/generated/GolampiParser.php';
require_once __DIR__ . '/generated/GolampiVisitor.php';
require_once __DIR__ . '/generated/GolampiBaseVisitor.php';

// modelos
require_once __DIR__ . '/models/Symbol.php';
require_once __DIR__ . '/models/ErrorEntry.php';

// interprete
require_once __DIR__ . '/interpreter/FlowTypes.php';
require_once __DIR__ . '/interpreter/Invocable.php';
require_once __DIR__ . '/interpreter/Environment.php';
require_once __DIR__ . '/interpreter/ErrorHandler.php';
require_once __DIR__ . '/interpreter/FuncionUsuario.php';
require_once __DIR__ . '/interpreter/Interpreter.php';

// reportes
require_once __DIR__ . '/reports/SymbolTableReport.php';
require_once __DIR__ . '/reports/ErrorReport.php';
