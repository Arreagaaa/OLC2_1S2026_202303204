# Diagrama de Clases

Diagrama resumido de la arquitectura principal del proyecto.

```mermaid
classDiagram
    class Visitor {
        +visitProgramRule()
        +visitFunctionDeclaration()
        +visitIfStmtRule()
        +visitForClassic()
        +visitBuiltinLen()
    }

    class Interpreter {
        +console
        +env
        +errors
        +visitProgramRule()
        +visitFunctionDeclaration()
        +visitBlockStmt()
        +getSymbolTable()
    }

    class CodeGen {
        +generateProgram()
        +compileFunctionDeclaration()
        +compileUserFuncCall()
        +compileExpr()
        +emitArrayAddress1D()
    }

    class Environment {
        +declare()
        +assign()
        +get()
        +getLocal()
    }

    class FuncionUsuario {
        +invoke()
    }

    class Symbol {
        +id
        +tipo
        +valor
        +clase
        +ambito
        +offset
    }

    class ErrorEntry {
        +tipo
        +descripcion
        +fila
        +columna
    }

    class SymbolTableReport {
        +toHtml()
    }

    class ErrorReport {
        +toHtml()
    }

    class execute.php {
        +POST codigo
        +returns JSON
    }

    Visitor --|> Interpreter
    Interpreter --> Environment
    Interpreter --> FuncionUsuario
    Interpreter --> Symbol
    Interpreter --> ErrorEntry
    CodeGen --> Symbol
    execute.php --> Visitor
    execute.php --> CodeGen
    execute.php --> SymbolTableReport
    execute.php --> ErrorReport
```

## Lectura rapida
- `Visitor` mantiene la capa semantica/interprete.
- `CodeGen` produce el ARM64.
- `execute.php` coordina analisis, codegen y serializacion JSON.
- `SymbolTableReport` y `ErrorReport` generan los reportes HTML usados por la GUI.