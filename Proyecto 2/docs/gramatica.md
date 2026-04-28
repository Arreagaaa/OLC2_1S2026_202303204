# Gramatica Golampi

Resumen de la gramatica implementada en `grammar/Golampi.g4`.

## Estructura general
- El programa se compone de declaraciones globales y funciones.
- La regla inicial es `program`.
- Se permiten declaraciones top-level de variables, constantes y funciones.

## Declaraciones
- `var` para variables con tipo explicito o inferido.
- `const` para constantes.
- Declaraciones cortas con `:=`.
- Asignaciones simples y compuestas (`=`, `+=`, `-=`, `*=`, `/=`).

## Tipos soportados
- Enteros: `int`, `int32`, `int64`.
- Flotantes: `float32`, `float64`.
- `bool`, `string`, `rune`.
- Arreglos 1D y multidimensionales.

## Expresiones
- Literales enteros, flotantes, cadenas, runas, booleanos y `nil`.
- Operadores aritméticos, relacionales y lógicos.
- Acceso a arreglos con uno o dos indices.
- Referencia con `&` y desreferencia con `*`.
- Llamadas a funciones y funciones embebidas.

## Control de flujo
- `if`, `else if`, `else`.
- `switch`, `case`, `default`.
- `for` clasico, condicional e infinito.
- `break`, `continue` y `return`.

## Funciones embebidas
- `fmt.Println(...)`
- `len(...)`
- `now()`
- `substr(...)`
- `typeOf(...)`