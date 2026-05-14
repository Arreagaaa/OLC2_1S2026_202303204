# OLC2 — Laboratorio OLC2 (1S2026)

Este repositorio contiene el material, tareas y proyectos del curso "OLC2" (Compiladores II), semestre 1S2026.

Contenido principal
- **Material/**: apuntes y recursos del curso.
- **Proyecto 2/**: proyecto principal con frontend y backend (incluye el intérprete, generadores y pruebas).
- **Proyecto1/**: implementación y versiones anteriores del proyecto del curso.
- **Tareas/**: tareas y ejemplos de apoyo.
- **grammar/**: gramática ANTLR original.
- **test/**: scripts de prueba y entradas de ejemplo.

Limpieza realizada
- Se han eliminado carpetas generadas y dependencias pesadas que no deben subirse al control de versiones: `vendor/`, carpetas generadas de ANTLR (`.antlr/`, `generated/`) y archivos de artefactos como `*.interp` y `*.tokens`.
- Se creó un backup con los archivos/carpetas eliminados (si existían) en `/tmp/repo_cleanup_backup.tar.gz` en la máquina local. Revísalo antes de eliminar ese backup permanentemente.

Por qué se eliminó esto
- Archivos generados por ANTLR y dependencias instaladas (`vendor`) pueden regenerarse o reinstalarse y suelen aumentar mucho el tamaño del repositorio innecesariamente.

Cómo regenerar lo eliminado
- Dependencias: ejecutar `composer install` dentro de cada subproyecto que lo requiera (por ejemplo `Proyecto 2/backend`).
- Artefactos ANTLR: volver a generar los ficheros desde la gramática `Golampi.g4` y las herramientas ANTLR usadas por el curso.

Archivos sensibles
- Se buscó por patrones comunes (password, secret, token, keys). No se detectaron archivos privados obvios en las ubicaciones inspeccionadas. Si tienes claves privadas, elimina o mueve esos archivos y añade las entradas necesarias a `.gitignore`.

Proyectos y notas de calificación
- **Proyecto 1:** 99/100
- **Proyecto 2:** 100/100

Instrucciones para el commit
1. Revisa el backup en `/tmp/repo_cleanup_backup.tar.gz` si necesitas recuperar algo.
2. Revisa los cambios con `git status` y `git diff`.
3. Añade, prueba y realiza el commit:

```
git add .
git commit -m "Limpieza: eliminar vendor y archivos generados; agregar .gitignore y README actualizado"
git push
```

Contacto
- Si quieres que recupere algún archivo del backup o excluya otros patrones, dime cuáles y lo ajusto.

---------------------------------------------------------
Última limpieza automática: 13 de mayo de 2026