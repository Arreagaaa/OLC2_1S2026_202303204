grammar Golampi;

// ─── tokens ignorados ─────────────────────────────────────────────────────────
WS          : [ \t\r\n\u000B\u000C\u0000]+ -> channel(HIDDEN) ;
LINE_COMMENT: '//' ~[\r\n]* -> channel(HIDDEN) ;
BLOCK_COMMENT: '/*' (BLOCK_COMMENT | .)*? '*/' -> channel(HIDDEN) ;

// ─── palabras clave ───────────────────────────────────────────────────────────
FUNC    : 'func' ;
VAR     : 'var' ;
CONST   : 'const' ;
IF      : 'if' ;
ELSE    : 'else' ;
FOR     : 'for' ;
SWITCH  : 'switch' ;
CASE    : 'case' ;
DEFAULT : 'default' ;
RETURN  : 'return' ;
BREAK   : 'break' ;
CONTINUE: 'continue' ;
NIL     : 'nil' ;
TRUE    : 'true' ;
FALSE   : 'false' ;

// tipos
INT32   : 'int32' ;
FLOAT32 : 'float32' ;
BOOL    : 'bool' ;
RUNE_T  : 'rune' ;
STRING_T: 'string' ;

// ─── literales ────────────────────────────────────────────────────────────────
INT_LIT   : [0-9]+ ;
FLOAT_LIT : [0-9]+ '.' [0-9]+ ;
RUNE_LIT  : '\'' (~['\\] | '\\' .) '\'' ;
STRING_LIT: '"' (~["\\] | '\\' .)* '"' ;

// ─── identificador ────────────────────────────────────────────────────────────
ID : [a-zA-Z_][a-zA-Z0-9_]* ;

// ─── operadores y delimitadores ───────────────────────────────────────────────
PLUS_ASSIGN : '+=' ;
MINUS_ASSIGN: '-=' ;
STAR_ASSIGN : '*=' ;
SLASH_ASSIGN: '/=' ;
SHORT_DECL  : ':=' ;
PLUS        : '+' ;
MINUS       : '-' ;
STAR        : '*' ;
SLASH       : '/' ;
PERCENT     : '%' ;
AMP         : '&' ;
EQ          : '==' ;
NEQ         : '!=' ;
LTE         : '<=' ;
GTE         : '>=' ;
LT          : '<' ;
GT          : '>' ;
AND         : '&&' ;
OR          : '||' ;
NOT         : '!' ;
ASSIGN      : '=' ;
LPAREN      : '(' ;
RPAREN      : ')' ;
LBRACE      : '{' ;
RBRACE      : '}' ;
LBRACKET    : '[' ;
RBRACKET    : ']' ;
COMMA       : ',' ;
SEMICOLON   : ';' ;
COLON       : ':' ;
DOT         : '.' ;

// ─── programa ─────────────────────────────────────────────────────────────────
program
    : topDecl* EOF                                          # ProgramRule
    ;

topDecl
    : funcDecl                                              # TopFuncDecl
    | varDecl SEMICOLON                                     # TopVarDecl
    | constDecl SEMICOLON                                   # TopConstDecl
    ;

// ─── declaraciones de función ─────────────────────────────────────────────────
funcDecl
    : FUNC ID LPAREN paramList? RPAREN returnType? block    # FunctionDeclaration
    ;

returnType
    : LPAREN typeList RPAREN                                # MultiReturn
    | typeRef                                               # SingleReturn
    ;

typeList
    : typeRef (COMMA typeRef)*
    ;

paramList
    : param (COMMA param)*
    ;

param
    : STAR? typeRef ID                                      # ParamDecl
    ;

// ─── sentencias ───────────────────────────────────────────────────────────────
block
    : LBRACE stmt* RBRACE                                   # BlockStmt
    ;

stmt
    : varDecl SEMICOLON                                     # VarDeclStmt
    | constDecl SEMICOLON                                   # ConstDeclStmt
    | shortDecl SEMICOLON                                   # ShortDeclStmt
    | assignment SEMICOLON                                  # AssignStmt
    | compoundAssign SEMICOLON                              # CompoundAssignStmt
    | RETURN exprList? SEMICOLON                            # ReturnStmt
    | BREAK SEMICOLON                                       # BreakStmt
    | CONTINUE SEMICOLON                                    # ContinueStmt
    | ifStmt                                                # IfStmtWrap
    | switchStmt                                            # SwitchStmtWrap
    | forStmt                                               # ForStmtWrap
    | callExpr SEMICOLON                                    # CallStmt
    | fmtPrintln SEMICOLON                                  # PrintlnStmt
    | arrayAssign SEMICOLON                                 # ArrayAssignStmt
    ;

// ─── declaración de variables ──────────────────────────────────────────────────
varDecl
    : VAR typeRef ID (ASSIGN expr)?                         # VarDeclSimple
    | VAR typeRef ID LBRACKET INT_LIT RBRACKET              # VarArrayDecl1D
    | VAR typeRef ID LBRACKET INT_LIT RBRACKET
        LBRACKET INT_LIT RBRACKET                           # VarArrayDecl2D
    ;

constDecl
    : CONST typeRef ID ASSIGN expr                          # ConstDeclRule
    ;

shortDecl
    : idList SHORT_DECL exprList                            # ShortDeclRule
    ;

idList
    : ID (COMMA ID)*
    ;

exprList
    : expr (COMMA expr)*
    ;

assignment
    : ID ASSIGN expr                                        # SimpleAssign
    ;

compoundAssign
    : ID op=(PLUS_ASSIGN | MINUS_ASSIGN | STAR_ASSIGN | SLASH_ASSIGN) expr
                                                            # CompoundAssignRule
    ;

arrayAssign
    : ID LBRACKET expr RBRACKET ASSIGN expr                 # ArrayAssign1D
    | ID LBRACKET expr RBRACKET LBRACKET expr RBRACKET ASSIGN expr
                                                            # ArrayAssign2D
    ;

// ─── control de flujo ─────────────────────────────────────────────────────────
ifStmt
    : IF expr block (ELSE (ifStmt | block))?                # IfStmtRule
    ;

switchStmt
    : SWITCH expr? LBRACE caseClause* RBRACE                # SwitchStmtRule
    ;

caseClause
    : CASE exprList COLON stmt*                             # CaseClauseRule
    | DEFAULT COLON stmt*                                   # DefaultClause
    ;

forStmt
    : FOR block                                             # ForInfinite
    | FOR expr block                                        # ForWhile
    | FOR forInit SEMICOLON expr SEMICOLON forPost block    # ForClassic
    ;

forInit
    : shortDecl                                             # ForInitShort
    | assignment                                            # ForInitAssign
    ;

forPost
    : assignment                                            # ForPostAssign
    | compoundAssign                                        # ForPostCompound
    ;

// ─── expresiones ──────────────────────────────────────────────────────────────
expr
    : primary                                               # PrimaryExpr
    | ID                                                    # IdExpr
    | callExpr                                              # CallExprWrap
    | fmtPrintln                                            # FmtPrintlnExpr
    | ID LBRACKET expr RBRACKET                             # ArrayAccess1D
    | ID LBRACKET expr RBRACKET LBRACKET expr RBRACKET      # ArrayAccess2D
    | AMP ID                                                # RefExpr
    | STAR ID                                               # DerefExpr
    | LPAREN expr RPAREN                                    # GroupExpr
    | NOT expr                                              # NotExpr
    | MINUS expr                                            # NegExpr
    | expr op=(STAR | SLASH | PERCENT) expr                 # MulExpr
    | expr op=(PLUS | MINUS) expr                           # AddExpr
    | expr op=(LT | LTE | GT | GTE | EQ | NEQ) expr        # RelExpr
    | expr AND expr                                         # AndExpr
    | expr OR expr                                          # OrExpr
    ;

// ─── literales primarios ──────────────────────────────────────────────────────
primary
    : INT_LIT                                               # IntLit
    | FLOAT_LIT                                             # FloatLit
    | STRING_LIT                                            # StringLit
    | RUNE_LIT                                              # RuneLit
    | TRUE                                                  # TrueLit
    | FALSE                                                 # FalseLit
    | NIL                                                   # NilLit
    | LBRACKET INT_LIT RBRACKET typeRef
        LBRACE (expr (COMMA expr)*)? RBRACE                 # ArrayLit1D
    | LBRACKET INT_LIT RBRACKET LBRACKET INT_LIT RBRACKET typeRef
        LBRACE (LBRACE (expr (COMMA expr)*)? RBRACE
        (COMMA LBRACE (expr (COMMA expr)*)? RBRACE)*)? RBRACE
                                                            # ArrayLit2D
    ;

// ─── llamadas ─────────────────────────────────────────────────────────────────
callExpr
    : ID LPAREN argList? RPAREN                             # UserFuncCall
    ;

fmtPrintln
    : 'fmt' DOT 'Println' LPAREN argList? RPAREN            # FmtPrintlnCall
    ;

argList
    : expr (COMMA expr)*
    ;

// ─── tipos ────────────────────────────────────────────────────────────────────
typeRef
    : INT32
    | FLOAT32
    | BOOL
    | RUNE_T
    | STRING_T
    | LBRACKET INT_LIT RBRACKET typeRef
    | STAR typeRef
    ;
