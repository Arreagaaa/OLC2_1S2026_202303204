grammar Grammar;

program: statement+ EOF;

statement:
	ID '=' expression				# Assign
	| 'print' '(' ID indices ')'	# Print;

indices: index+;

index: '[' NUM ']';

expression:
	'[' ']'					# EmptyList
	| '[' list_elements ']'	# ElementList;

list_elements: value (',' value)* # ValueList;

value: NUM # ValueNum | expression # ValueExpr;

ID: [a-zA-Z_][a-zA-Z0-9_]*;
NUM: [0-9]+;
WS: [ \r\n\t]+ -> skip;