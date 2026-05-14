grammar Grammar;

start
    : decl EOF
    ;

decl
    : type list
    ;

type
    : 'int'
    | 'float'
    ;

list
    : ID (',' ID)*
    ;

ID
    : [a-zA-Z]+
    ;

WS
    : [ \t\n\r]+ -> skip
    ;
