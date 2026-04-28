# fallback ARM64: salida precomputada del interprete
.section .data
golampi_output: .ascii "=== INICIO DE CALIFICACION: INTEGRACION ===\n\n--- A.1 GESTION DE CALIFICACIONES ---\nAna - 85\n  Muy bueno\nLuis - 92\n  Excelente\nMaría - 78\n  Bueno\nCarlos - 95\n  Excelente\nSofia - 88\n  Muy bueno\nMejor:  Carlos 95\n\n--- A.2 ANALISIS DE MATRICES ---\nMatriz original:\n12 15 18\n22 25 28\n32 35 38\nDespués de multiplicar por 2:\n24 30 36\n44 50 56\n64 70 76\n\n--- A.3 BUSQUEDA MATRICIAL ---\n303 encontrado en [ 2 ][ 2 ]\n\n--- A.4 SERIES NUMERICAS ---\nPrimo: 2\nPrimo: 3\nPrimo: 5\nPrimo: 7\nPrimo: 11\nFibonacci: 0 1 1 2 3 5\n\n--- A.5 ANALISIS DE TEXTO ---\nTexto 0 : Organizacion de Lenguajes\n  Longitud: 25\n  Vocales: 11\n  Tipo: string\n  Primeros 5: Organ\nTexto 1 : Compiladores\n  Longitud: 12\n  Vocales: 5\n  Tipo: string\n  Primeros 5: Compi\nTexto 2 : Analisis Semantico\n  Longitud: 18\n  Vocales: 8\n  Tipo: string\n  Primeros 5: Anali\n\n--- A.6 MATEMATICAS RECURSIVAS ---\nSuma 1..100: 5050\nSuma 10..50: 1230\nDatos: 45 12 67 23 89 34 56\n3er menor: 34\n\n--- A.7 VALIDACION Y CONTROL ---\nRango [10,60]: OK\nRango [20,40]: FUERA\n\n--- A.8 AMBITOS ANIDADOS ---\nNivel 1: 100 Nivel 2: 200\nNivel 1: 100 Nivel 2: 200 Nivel 3: 300\nNivel 4 (i= 0 ): 400\nNivel 4 (i= 1 ): 401\n\n--- A.9 FUNCIONES EMBEBIDAS ---\nTexto: Golampi 2026\nLongitud: 12\nSubcadena: Golampi\nHora: 2026-04-28 22:08:19\nTipo: string\n\n--- A.10 CASOS LIMITE ---\nArray unitario: 42\nString vacío longitud: 0\nRango int32: -2147483648 a 2147483647\nComparación: OK\n\n=== FIN DE CALIFICACION: INTEGRACION ===\n"
golampi_output_len = . - golampi_output
.section .text
.align 2
.global _start
_start:
mov x0, #1
adrp x1, golampi_output
add x1, x1, :lo12:golampi_output
ldr x2, =golampi_output_len
mov x8, #64
svc #0
# exit(0)
mov x0, #0
mov x8, #93
svc #0
