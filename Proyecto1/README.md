# Golampi Interpreter — OLC2 Sección B

Intérprete de un lenguaje académico inspirado en Go, desarrollado con ANTLR4 + PHP + HTML/CSS/JS.

---

## Requisitos

- PHP >= 8.1 con el servidor integrado (`php -S`)
- Java >= 11 (para generar archivos ANTLR4)
- Composer

---

## Instalacion

```bash
cd Proyecto1

# instalar dependencias PHP (runtime de ANTLR4)
composer install

# generar lexer/parser PHP desde la gramática
bash build.sh
```

---

## Ejecutar el servidor

```bash
cd Proyecto1

# servir desde la raiz del proyecto para que las rutas relativas funcionen
php -S localhost:8080
```

Luego abrir en el navegador: `http://localhost:8080/frontend/index.html`

---

## Estructura del proyecto

```
Proyecto1/
├── grammar/
│   └── Golampi.g4              # gramática ANTLR4
├── backend/
│   ├── bootstrap.php           # carga de dependencias
│   ├── generated/              # archivos PHP generados por ANTLR4 (no editar)
│   ├── interpreter/
│   │   ├── Interpreter.php     # visitor principal
│   │   ├── Environment.php     # entornos anidados
│   │   ├── FuncionUsuario.php  # funciones definidas por usuario
│   │   ├── ErrorHandler.php    # recoleccion de errores
│   │   ├── FlowTypes.php       # break/continue/return como valores
│   │   └── Invocable.php       # contrato para funciones
│   ├── models/
│   │   ├── Symbol.php          # entrada de tabla de simbolos
│   │   └── ErrorEntry.php      # entrada de tabla de errores
│   ├── reports/
│   │   ├── SymbolTableReport.php
│   │   └── ErrorReport.php
│   └── api/
│       └── execute.php         # endpoint HTTP POST
├── frontend/
│   ├── index.html
│   ├── css/style.css
│   └── js/
│       ├── editor.js
│       ├── api.js
│       └── reports.js
├── docs/
├── build.sh                    # genera archivos ANTLR4
├── composer.json
└── .htaccess
```

---

## Flujo de ejecucion

1. El usuario escribe codigo Golampi en el editor y presiona **Ejecutar**.
2. `api.js` hace POST a `backend/api/execute.php` con `{ codigo: '...' }`.
3. PHP instancia `GolampiLexer` y `GolampiParser` (generados por ANTLR4).
4. El `Interpreter` (visitor) recorre el CST: evalua, ejecuta, captura errores.
5. `execute.php` serializa `{ output, errors, symbols, errorsHtml, symbolsHtml }` como JSON.
6. `api.js` muestra la salida en consola; `reports.js` maneja la visualizacion de reportes.
