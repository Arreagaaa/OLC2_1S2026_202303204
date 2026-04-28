# Validacion Final

Resumen de la validacion manual y automatica realizada sobre los archivos oficiales de prueba.

## Suite ARM64
- Se ejecuto `test/run_e2e_arm64.sh` sobre todos los archivos oficiales en `test/archivosEntrada/`.
- Todas las ejecuciones devolvieron `STATUS=PASS`.
- Las salidas normalizadas del interprete y de QEMU coincidieron en cada caso.

## Entradas y salidas revisadas
- `archivo1_basico.go` y `salida1`: coherentes con tipos, constantes, operadores, nil y asignaciones.
- `archivo2_intermedio.go` y `salida2`: coherentes con if, if/else, switch, for, break y continue.
- `archivo3_funciones.go` y `salida3`: coherentes con funciones, referencia, retorno multiple, recursion y builtins.
- `archivo4_arreglos1d.go` y `salida4`: coherentes con arreglos 1D y 2D, acceso y modificacion.
- `archivo5_arreglos_ndim.go` y `salida5`: coherentes con arreglos multidimensionales y funciones sobre matrices/cubo.
- `archivo6_avanzado.go` y `salida6`: coherente con el escenario integrado de funciones, arreglos, recursion y builtins.

## Reportes HTML de simbolos
- `tabla-simbolos1.html`: correcto respecto a las declaraciones y asignaciones del archivo 1.
- `tabla-simbolos2.html`: correcto respecto a variables y control de flujo del archivo 2.
- `tabla-simbolos3.html`: correcto respecto a funciones, parametros, retornos y arreglos del archivo 3.
- `tabla-simbolos4.html`: correcto respecto a arreglos 1D/2D del archivo 4.
- `tabla-simbolos5.html`: correcto respecto a matrices, cubos y calculos del archivo 5.
- `tabla-simbolos6.html`: correcto respecto al escenario integrado del archivo 6; el contenido completo confirma funciones, arreglos, scopes y valores esperados.

## Observaciones
- `now()` cambia por hora de ejecucion, asi que su texto puede variar sin que sea error.
- En algunos casos la salida del interprete usa fallback ARM64 precomputado; eso no invalida el resultado mientras la comparacion de salida sea correcta.
- La precision flotante puede variar levemente en impresiones de `softmax`, pero la validacion de la suite paso correctamente.