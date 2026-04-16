# Golampi Compiler — OLC2 Sección B

Compilador de un lenguaje académico inspirado en Go, desarrollado con ANTLR4 + PHP + HTML/CSS/JS.

**Estudiante:** 202303204  
**Curso:** Organización de Lenguajes y Compiladores 2 — 1er semestre 2026

---

## Requisitos

| Herramienta | Version minima |
|-------------|----------------|
| PHP         | 8.1 (servidor integrado incluido) |
| Java        | 11 (solo para regenerar el parser con ANTLR4) |
| Composer    | 2.x |
| ANTLR4      | 4.13 (JAR incluido en `build.sh`) |

---

## Instalacion (primera vez)

```bash
cd "Proyecto 2"

# instalar la dependencia PHP del runtime ANTLR4
composer install

# generar lexer y parser PHP a partir de grammar/Golampi.g4
bash build.sh
```

Los archivos PHP generados se escriben en `backend/generated/` y **no deben editarse manualmente**.  
Solo es necesario volver a ejecutar `build.sh` si se modifica `grammar/Golampi.g4`.

---

## Ejecutar el servidor

```bash
cd "Proyecto 2"

# cualquier puerto libre sirve; 8181 es el usado durante el desarrollo
php -S localhost:8181 -t .
```

Luego abrir en el navegador: `http://localhost:8181/frontend/index.html`

> El flag `-t .` es obligatorio para que PHP sirva la raiz del proyecto y las
> rutas `backend/api/execute.php` resuelvan correctamente.

---

## Estructura del proyecto

```
Proyecto 2/
├── grammar/
│   └── Golampi.g4              # gramatica ANTLR4 (fuente de verdad)
├── backend/
│   ├── bootstrap.php           # carga de dependencias
│   ├── generated/              # archivos PHP generados por ANTLR4 (no editar)
│   ├── interpreter/
│   │   ├── Interpreter.php     # visitor principal (evaluacion del AST)
│   │   ├── Environment.php     # entornos anidados (scopes)
│   │   ├── FuncionUsuario.php  # funciones definidas por el usuario
│   │   ├── ErrorHandler.php    # recoleccion de errores semanticos
│   │   ├── FlowTypes.php       # break / continue / return como valores de control
│   │   └── Invocable.php       # interfaz para funciones
│   ├── models/
│   │   ├── Symbol.php          # entrada de la tabla de simbolos
│   │   └── ErrorEntry.php      # entrada de la tabla de errores
│   ├── reports/
│   │   ├── SymbolTableReport.php
│   │   └── ErrorReport.php
│   └── api/
│       └── execute.php         # endpoint HTTP POST recibe JSON {codigo}
├── frontend/
│   ├── index.html
│   ├── css/style.css
│   └── js/
│       ├── editor.js           # editor CodeMirror
│       ├── api.js              # fetch al backend, guarda window._lastResponse
│       └── reports.js          # visualizacion y descarga de reportes
├── test/
│   └── test1/                  # archivos de prueba de calificacion
│       ├── basicos.go
│       ├── intermedio.go
│       ├── arreglos.go
│       └── funciones/
│           ├── funciones.go
│           └── embebidas.go
├── docs/
├── build.sh                    # genera archivos ANTLR4
└── composer.json
```

---

## Flujo de ejecucion

1. El usuario escribe codigo Golampi en el editor y presiona **Ejecutar**.
2. `api.js` hace POST a `backend/api/execute.php` con `{ codigo: '...' }`.
3. PHP instancia `GolampiLexer` y `GolampiParser` (generados por ANTLR4).
4. El `Interpreter` (visitor) recorre el CST: evalua, ejecuta y captura errores.
5. `execute.php` retorna `{ output, errors, symbols, errorsHtml, symbolsHtml }` como JSON.
6. `api.js` muestra la salida en la consola del editor.
7. `reports.js` permite ver y descargar la tabla de simbolos y la tabla de errores.

---

## Funcionalidades implementadas

### Tipos y declaraciones
- `var nombre tipo = valor` — declaracion larga con tipo explicito
- `nombre := expr` — declaracion corta con inferencia de tipo
- `const nombre = valor` — constantes
- Tipos: `int32`, `float32`, `float64`, `bool`, `int`, `string`, `rune`
- Arreglos 1D: `[N]tipo{...}` y 2D: `[N][M]tipo{...}`
- Valor nil con `<nil>` en salida y en comparaciones

### Control de flujo
- `if / else if / else`
- `switch / case / default`
- `for` clasico, condicional (while) e infinito
- `break` y `continue`

### Funciones
- Declaracion con y sin parametros
- Paso por valor y paso por referencia (`*tipo`)
- Retorno multiple
- Recursion
- Hoisting (uso antes de declaracion)

### Funciones embebidas
- `fmt.Println(...)` — impresion con multiples argumentos
- `len(cadena | arreglo)` — longitud
- `typeOf(expr)` — tipo del valor (`int`, `float64`, `int32`, `bool`, `string`, `[N]tipo`, ...)
- `substr(cadena, inicio, fin)` — subcadena por indices
- `now()` — fecha y hora actual del sistema

### Interfaz web
- Editor con resaltado de sintaxis (CodeMirror)
- Consola de salida
- Tabla de simbolos (ver en panel / descargar como `.html`)
- Tabla de errores (ver en panel / descargar como `.html`)
- Descargar resultado de consola como `.txt`

---

## Archivos de prueba verificados

| Archivo | Descripcion | Estado |
|---------|-------------|--------|
| `test/test1/basicos.go` | Tipos, operadores, nil, constantes | Pasa |
| `test/test1/intermedio.go` | if/else, switch, for, break, continue | Pasa |
| `test/test1/arreglos.go` | Arreglos 1D y 2D, funciones con matrices | Pasa |
| `test/test1/funciones/embebidas.go` | len, typeOf, substr, now | Pasa |
| `test/test1/funciones/funciones.go` | parametros, referencias, multi-retorno, recursion | Pasa |
| `test/test1/funciones/test_recursivo.go` | fibonacci, potencia, hanoi, bubble sort byRef, MCD, primos | Pasa |

---

## Notas de desarrollo

- Los semicolons son **opcionales** en todos los statements (igual que en Go real).
- El parser ANTLR4 se genera desde `grammar/Golampi.g4` y se deposita en `backend/generated/`.
- `typeOf` retorna el tipo declarado del simbolo, no el tipo PHP subyacente.
  - Literal entero (`42`) → `int`
  - Literal flotante (`3.14`) → `float64`
  - Literal rune (`'X'`) → `int32`
  - Variable declarada `var x float32` → `float32`
- Los arreglos se pasan por referencia usando el mecanismo `*tipo`; las escrituras
  dentro de la funcion se propagan al entorno del llamador via `Symbol::$refName`/`$refEnv`.
