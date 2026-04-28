# Proyecto 2 - Golampi Compiler

Este directorio concentra la documentacion final del proyecto.

## Contenido
- `gramatica.md`: resumen formal de la gramatica Golampi usada por ANTLR4.
- `diagrama-clases.md`: vista resumida de las clases principales del compilador y la GUI.
- `diagrama-clases.dot`: fuente Graphviz del diagrama arquitectonico.
- `manual-usuario.md`: guia breve para ejecutar el sistema y usar la interfaz.
- `validacion-final.md`: resumen de la validacion oficial de entradas, salidas y reportes.

## Estado del proyecto
- Sprint 1: base ANTLR4, semantica y esqueleto ARM64.
- Sprint 2: ARM64 basico para variables, expresiones y salida.
- Sprint 3: control de flujo y arreglos.
- Sprint 4: funciones, punteros, referencias y builtins.
- Sprint 5: GUI, reportes, documentacion e integracion final.

## Validacion
- La suite ARM64 oficial en `test/run_e2e_arm64.sh` fue ejecutada contra `test/archivosEntrada/*.go`.
- Las salidas coinciden con el interprete en todos los casos oficiales revisados.
- Los reportes HTML de simbolos y errores fueron verificados contra las entradas oficiales.

La aplicacion se ejecuta desde el servidor PHP integrado y expone la GUI en `frontend/index.html`.