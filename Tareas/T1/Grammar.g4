grammar Grammar;

l : e
  ;

e : e '+' t     # Add
  | e '-' t     # Sub
  | t           # Et
  ;

t : t '*' f     # Product
  | t '/' f     # Div
  | f           # Tf
  ;

f : '(' e ')'   # Paren
  | DIGIT       # Int
  ;

DIGIT : [0-9]+;
WS : [ \t\r\n]+ -> skip;