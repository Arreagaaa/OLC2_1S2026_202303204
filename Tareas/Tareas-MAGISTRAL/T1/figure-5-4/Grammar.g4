grammar Grammar;

l : e EOF;

e  : t ep                            # Expr
	;

ep : '+' t ep                        # EpsSum
	|                                 # EpsEmpty
	;

t  : f tp                            # Term
	;

tp : '*' f tp                        # EpsMul
	|                                 # EpsEmptyMul
	;

f  : '(' e ')'                       # Brace
	| DIGIT                            # Digit
	;

DIGIT : [0-9]+ ;

WS : [ \t\r\n]+ -> skip ;


