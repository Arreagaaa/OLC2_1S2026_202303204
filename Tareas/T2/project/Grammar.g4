grammar Grammar;

p : stmt* EOF #Program;

stmt
: ID ':=' e  #AssignStatement
| 'print' '(' ID ')' #PrintStatement
| 'if' '(' cond ')' block ('else' block)? #IfStatement
;

block : '{' stmt* '}' ;

cond : e op=('=='|'!='|'<'|'>'|'<='|'>=') e # Condition ;

e : e op=('+' | '-') term #AddExpr
| term #TermExpr
;

term : term op=('*' | '/') factor #MulExpr
| factor #FactorExpr
;

factor
: '(' e ')' #GroupExpr
| INT # IntExpr
| ID # IdExpr
;

INT : [0-9]+ ;
ID  : [a-zA-Z_][a-zA-Z0-9_]* ;
WS  : [ \t\r\n]+ -> skip ;