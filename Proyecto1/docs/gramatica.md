# Gramática Formal — Golampi

**Proyecto:** Golampi Interpreter  
**Curso:** Organización de Lenguajes y Compiladores 2 — Sección B  
**USAC — 1er Semestre 2026**

---

## 1. Descripción General

Golampi es un lenguaje académico estáticamente tipado inspirado en Go. La gramática
está definida en formato ANTLR4 (archivo `grammar/Golampi.g4`) y genera un lexer y
parser en PHP que procesan código fuente Golampi.

---

## 2. Reglas Léxicas (Tokens)

### 2.1 Tokens ignorados

| Token | Patrón | Canal |
|-------|--------|-------|
| `WS` | `[ \t\r\n]+` | HIDDEN |
| `LINE_COMMENT` | `// ~[\r\n]*` | HIDDEN |
| `BLOCK_COMMENT` | `/* ... */` (anidables) | HIDDEN |

### 2.2 Palabras Reservadas

```
func      var       const     if        else
for       switch    case      default   return
break     continue  nil       true      false
```

### 2.3 Tipos Primitivos

```
int32     float32   int       int64     float64
bool      rune      string
```

### 2.4 Literales

| Token | Patrón | Ejemplo |
|-------|--------|---------|
| `INT_LIT` | `[0-9]+` | `42` |
| `FLOAT_LIT` | `[0-9]+ '.' [0-9]+` | `3.14` |
| `RUNE_LIT` | `'\'' (~['\\] \| '\\' .) '\''` | `'A'` |
| `STRING_LIT` | `'"' (~["\\] \| '\\' .)* '"'` | `"hola"` |

### 2.5 Identificadores

```
ID : [a-zA-Z_][a-zA-Z0-9_]*
```

Los identificadores distinguen mayúsculas y minúsculas (`Foo ≠ foo`).

### 2.6 Operadores y Delimitadores

| Símbolo | Token | Símbolo | Token |
|---------|-------|---------|-------|
| `++` | `INC` | `--` | `DEC` |
| `+=` | `PLUS_ASSIGN` | `-=` | `MINUS_ASSIGN` |
| `*=` | `STAR_ASSIGN` | `/=` | `SLASH_ASSIGN` |
| `:=` | `SHORT_DECL` | `=` | `ASSIGN` |
| `+` | `PLUS` | `-` | `MINUS` |
| `*` | `STAR` | `/` | `SLASH` |
| `%` | `PERCENT` | `&` | `AMP` |
| `==` | `EQ` | `!=` | `NEQ` |
| `<=` | `LTE` | `>=` | `GTE` |
| `<` | `LT` | `>` | `GT` |
| `&&` | `AND` | `\|\|` | `OR` |
| `!` | `NOT` | `.` | `DOT` |
| `(` `)` | `LPAREN RPAREN` | `{` `}` | `LBRACE RBRACE` |
| `[` `]` | `LBRACKET RBRACKET` | `,` | `COMMA` |
| `;` | `SEMICOLON` | `:` | `COLON` |

> Los puntos y coma (`;`) son **opcionales** en todos los statements — comportamiento idéntico al de Go real.

---

## 3. Reglas Sintácticas (Gramática BNF/ANTLR)

### 3.1 Programa

```antlr
program
    : topDecl* EOF
    ;

topDecl
    : funcDecl
    | varDecl SEMICOLON?
    | constDecl SEMICOLON?
    ;
```

### 3.2 Declaración de Funciones

```antlr
funcDecl
    : FUNC ID LPAREN paramList? RPAREN returnType? block
    ;

returnType
    : LPAREN typeList RPAREN     // múltiples retornos: (int32, bool)
    | typeRef                    // retorno simple: int32
    ;

typeList
    : typeRef (COMMA typeRef)*
    ;

paramList
    : param (COMMA param)*
    ;

param
    : ID typeRef
    ;
```

**Ejemplos:**
```go
func suma(a int32, b int32) int32 { ... }
func dividir(a int32, b int32) (int32, bool) { ... }
func bonificar(arr *[5]int32, extra int32) { ... }
```

### 3.3 Bloque y Sentencias

```antlr
block
    : LBRACE stmt* RBRACE
    ;

stmt
    : varDecl SEMICOLON?
    | constDecl SEMICOLON?
    | shortDecl SEMICOLON?
    | assignment SEMICOLON?
    | compoundAssign SEMICOLON?
    | RETURN exprList? SEMICOLON?
    | BREAK SEMICOLON?
    | CONTINUE SEMICOLON?
    | ifStmt
    | switchStmt
    | forStmt
    | callExpr SEMICOLON?
    | fmtPrintln SEMICOLON?
    | arrayAssign SEMICOLON?
    | ID INC SEMICOLON?
    | ID DEC SEMICOLON?
    | builtinCall SEMICOLON?
    ;
```

### 3.4 Declaraciones de Variables

```antlr
// Declaración larga
varDecl
    : VAR idList typeRef (ASSIGN exprList)?
    ;

// Declaración corta (inferencia de tipo)
shortDecl
    : idList SHORT_DECL exprList
    ;

// Constante
constDecl
    : CONST ID typeRef ASSIGN expr
    ;

idList
    : ID (COMMA ID)*
    ;
```

**Ejemplos:**
```go
var x int32 = 100           // declaración larga con inicialización
var y float32               // declaración larga sin inicialización (valor por defecto)
var a, b int32 = 10, 20     // múltiple declaración larga
z := 42                     // declaración corta
p, q := "hola", true        // múltiple declaración corta
const PI float32 = 3.14159  // constante
```

### 3.5 Asignaciones

```antlr
assignment
    : ID ASSIGN expr
    ;

compoundAssign
    : ID op=(PLUS_ASSIGN | MINUS_ASSIGN | STAR_ASSIGN | SLASH_ASSIGN) expr
    ;

arrayAssign
    : ID LBRACKET expr RBRACKET ASSIGN expr                          // a[i] = val
    | ID LBRACKET expr RBRACKET LBRACKET expr RBRACKET ASSIGN expr   // m[i][j] = val
    ;
```

### 3.6 Control de Flujo

```antlr
// IF
ifStmt
    : IF expr block (ELSE (ifStmt | block))?
    ;

// SWITCH
switchStmt
    : SWITCH expr? LBRACE caseClause* RBRACE
    ;

caseClause
    : CASE exprList COLON stmt*    // case 1, 2, 3:
    | DEFAULT COLON stmt*
    ;

// FOR — tres variantes
forStmt
    : FOR block                                          // for infinito
    | FOR expr block                                     // for condicional (while)
    | FOR forInit SEMICOLON expr SEMICOLON forPost block // for clásico
    ;

forInit
    : shortDecl
    | assignment
    ;

forPost
    : assignment
    | compoundAssign
    | ID INC
    | ID DEC
    ;
```

**Ejemplos:**
```go
// For clásico
for i := 0; i < 10; i++ { ... }

// For condicional (while)
for x > 0 { x-- }

// For infinito
for { break }

// Switch con múltiples cases por cláusula
switch dia {
case 1, 2: fmt.Println("Inicio")
case 3:    fmt.Println("Mitad")
default:   fmt.Println("Otro")
}
```

### 3.7 Expresiones

```antlr
expr
    : primary                                               // literal
    | ID                                                    // identificador
    | callExpr                                              // llamada a función
    | builtinCall                                           // len, now, substr, typeOf
    | fmtPrintln                                            // fmt.Println(...)
    | ID LBRACKET expr RBRACKET                             // arreglo[i]
    | ID LBRACKET expr RBRACKET LBRACKET expr RBRACKET      // matriz[i][j]
    | AMP ID                                                // &variable (referencia)
    | STAR ID                                               // *puntero (desreferencia)
    | LPAREN expr RPAREN                                    // (expr)
    | NOT expr                                              // !expr
    | MINUS expr                                            // -expr
    | expr (STAR | SLASH | PERCENT) expr                    // *, /, %
    | expr (PLUS | MINUS) expr                              // +, -
    | expr (LT | LTE | GT | GTE | EQ | NEQ) expr           // relacionales
    | expr AND expr                                         // && (cortocircuito)
    | expr OR expr                                          // || (cortocircuito)
    ;
```

**Precedencia de operadores (menor a mayor):**

| Nivel | Operadores | Asociatividad |
|-------|-----------|---------------|
| 1 | `\|\|` | Izquierda |
| 2 | `&&` | Izquierda |
| 3 | `<` `<=` `>` `>=` `==` `!=` | Izquierda |
| 4 | `+` `-` | Izquierda |
| 5 | `*` `/` `%` | Izquierda |
| 6 | `!` `-` (unario) | Derecha |
| 7 | `&` `*` (puntero), `[]`, llamadas | — |

### 3.8 Literales Primarios

```antlr
primary
    : INT_LIT                                          // 42
    | FLOAT_LIT                                        // 3.14
    | STRING_LIT                                       // "texto"
    | RUNE_LIT                                         // 'A'
    | TRUE | FALSE                                     // true, false
    | NIL                                              // nil
    | LBRACKET INT_LIT RBRACKET typeRef
        LBRACE (expr (COMMA expr)*)? RBRACE            // [5]int32{1,2,3,4,5}
    | LBRACKET INT_LIT RBRACKET LBRACKET INT_LIT RBRACKET typeRef
        LBRACE (LBRACE ... RBRACE)* RBRACE             // [2][2]int32{{1,2},{3,4}}
    ;
```

### 3.9 Llamadas a Funciones

```antlr
callExpr
    : ID LPAREN argList? RPAREN       // miFuncion(a, b)
    ;

fmtPrintln
    : 'fmt' DOT 'Println' LPAREN argList? RPAREN
    ;

builtinCall
    : 'len'    LPAREN expr RPAREN
    | 'now'    LPAREN RPAREN
    | 'substr' LPAREN expr COMMA expr COMMA expr RPAREN
    | 'typeOf' LPAREN expr RPAREN
    ;
```

### 3.10 Referencias de Tipo

```antlr
typeRef
    : INT32 | FLOAT32 | INT | INT64 | FLOAT64
    | BOOL | RUNE_T | STRING_T
    | LBRACKET INT_LIT RBRACKET typeRef   // arreglo: [5]int32
    | STAR typeRef                        // puntero: *int32
    ;
```

---

## 4. Tipos del Lenguaje

### 4.1 Tipos Primitivos

| Tipo | Descripción | Valor por Defecto |
|------|-------------|-------------------|
| `int32` | Entero de 32 bits con signo | `0` |
| `float32` | Punto flotante IEEE-754 de 32 bits | `0` |
| `int` | Entero (inferido en declaración corta con literal entero) | `0` |
| `float64` | Punto flotante de 64 bits (inferido en `:=` con literal flotante) | `0` |
| `bool` | Booleano `true` / `false` | `false` |
| `rune` | Carácter Unicode (alias semántico de `int32`) | `0` |
| `string` | Cadena de texto Unicode | `""` |

### 4.2 Tipos Compuestos

| Tipo | Ejemplo | Descripción |
|------|---------|-------------|
| Arreglo 1D | `[5]int32` | Arreglo de tamaño fijo |
| Arreglo 2D | `[3][3]float32` | Matriz de tamaño fijo |
| Puntero | `*int32` | Referencia a variable del llamador |

### 4.3 Inferencia de Tipo en Declaración Corta

| Literal | Tipo Inferido |
|---------|---------------|
| `42` (entero) | `int` |
| `3.14` (flotante) | `float64` |
| `'X'` (rune) | `int32` |
| `true` / `false` | `bool` |
| `"texto"` | `string` |
| `[N]T{...}` | `[N]T` |

---

## 5. Semántica de Operadores

### 5.1 Reglas de Promoción de Tipos

| Operandos | Resultado |
|-----------|-----------|
| `int32 + int32` | `int32` |
| `int32 + float32` | `float32` |
| `float32 + float32` | `float32` |
| `rune + rune` | `int32` |
| `string + string` | `string` (concatenación) |
| `int32 * string` | `string` (repetición) |
| `cualquiera op nil` | `nil` (error semántico) |

### 5.2 Cortocircuito Lógico

- `false && expr` → `false` sin evaluar `expr`
- `true || expr` → `true` sin evaluar `expr`

### 5.3 nil

- `nil` imprime como `<nil>`
- `nil == nil` retorna `<nil>` (por diseño del lenguaje)
- Cualquier operación aritmética con `nil` retorna `nil`

---

## 6. Funciones Embebidas

| Función | Firma | Descripción |
|---------|-------|-------------|
| `fmt.Println(...)` | `variádico → void` | Imprime argumentos separados por espacio + `\n` |
| `len(x)` | `string\|array → int32` | Longitud de cadena o número de elementos de arreglo |
| `now()` | `() → string` | Fecha y hora actual: `YYYY-MM-DD HH:MM:SS` |
| `substr(s, i, n)` | `(string, int, int) → string` | Subcadena desde índice `i` con longitud `n` |
| `typeOf(x)` | `any → string` | Nombre del tipo Golampi del argumento |

### typeOf — Comportamiento detallado

| Expresión | Resultado |
|-----------|-----------|
| Variable declarada con `:=` de literal entero | `"int"` |
| Variable declarada con `:=` de literal flotante | `"float64"` |
| Variable declarada con `:=` de literal rune | `"int32"` |
| Variable declarada `var x int32` | `"int32"` |
| Variable declarada `var x float32` | `"float32"` |
| Variable declarada `var x bool` | `"bool"` |
| Variable declarada `var x string` | `"string"` |
| Arreglo `[3]int32` | `"[3]int32"` |

---

## 7. Reglas de Ámbito (Scope)

- Cada bloque `{ }` crea un nuevo entorno anidado.
- La búsqueda de variables sigue la cadena de entornos padre (lexical scoping).
- Las funciones capturan el entorno del lugar donde están definidas (closure).
- **Hoisting:** todas las funciones del programa se registran antes de ejecutar `main()`, permitiendo usarlas antes de su definición textual.
- El paso por referencia (`*T`) crea un alias que propaga escrituras al entorno del llamador.
