grammar Grammar;

t : b c EOF # T_rule;

b : INT     # B_int
  | FLOAT   # B_float
  ;

c : LBRACK NUMBER RBRACK c # C_array
  |                        # C_empty
  ;

INT     : 'int' ;
FLOAT   : 'float' ;
LBRACK  : '[' ;
RBRACK  : ']' ;
NUMBER  : [0-9]+ ;
WS	    : [ \t\n\r]+ -> skip;