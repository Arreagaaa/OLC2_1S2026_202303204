grammar Grammar;

l
    : e
    ;

e
    : e '+' t      # Add
    | t            # ToTerm
    ;

t
    : t '*' f      # Mul
    | f            # ToFactor
    ;

f
    : '(' e ')'    # Par
    | DIGIT        # Int
    ;

DIGIT
    : [0-9]+
    ;

WS
    : [ \t\r\n]+ -> skip
    ;
