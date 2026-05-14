grammar Grammar;

line
    : statement * EOF
    ;

statement
    : NAME EQUALS expression        # Assign
    | expression                    # PrintExpr
    ;

expression
    : MINUS expression                              # Uminus
    | expression op=(TIMES | DIVIDE) expression     # MulDiv
    | expression op=(PLUS | MINUS) expression       # AddSub
    | NUMBER                                        # Number
    | NAME                                          # Name
    | LPAREN expression RPAREN                      # Parens
    ;

PLUS    : '+' ;
MINUS   : '-' ;
TIMES   : '*' ;
DIVIDE  : '/' ;
EQUALS  : '=' ;
LPAREN  : '(' ;
RPAREN  : ')' ;

NUMBER  : [0-9]+ ;
NAME    : [a-zA-Z_][a-zA-Z0-9_]* ;
