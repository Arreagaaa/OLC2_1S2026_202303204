grammar Grammar;

p : stmt* EOF #Program;

stmt
    : ID ':=' e                             #AssignStatement
    | 'print' '(' STRING ')'               #PrintStringStatement
    | 'print' '(' ID ')'                   #PrintStatement
    | 'if' cond block ('else' block)?      #IfStatement
    ;

block : '{' stmt* '}' ;

// Condición con cortocircuito: || tiene menor precedencia que &&
cond
    : cond '||' condAnd   #CondOrExpr
    | condAnd             #CondAndPass
    ;

condAnd
    : condAnd '&&' condRel  #CondAndExpr
    | condRel               #CondRelPass
    ;

condRel
    : e op=('=='|'!='|'<'|'>'|'<='|'>=') e  #CondRelExpr
    | '(' cond ')'                           #CondGroupExpr
    ;

e : e op=('+' | '-') term   #AddExpr
  | term                    #TermExpr
  ;

term
    : term op=('*' | '/') factor  #MulExpr
    | factor                      #FactorExpr
    ;

factor
    : '(' e ')'   #GroupExpr
    | INT         #IntExpr
    | ID          #IdExpr
    ;

INT    : [0-9]+ ;
ID     : [a-zA-Z_][a-zA-Z0-9_]* ;
// Acepta comillas ASCII y comillas tipograficas para evitar que
// entradas copiadas desde PDF/Word se tokenicen como IDs sueltos.
STRING
    : '"' (~["\r\n])* '"'
    | '“' (~[”\r\n])* '”'
    ;
WS     : [ \t\r\n]+ -> skip ;
