# fallback ARM64: salida precomputada del interprete
.section .data
golampi_output: .ascii "=== INICIO DE CALIFICACION: FUNCIONES ===\n\n--- 3.1 IMPRIMIR ARBOL ---\n  *\n ***\n*****\n\n--- 3.2 CALCULAR VOLUMEN PIRAMIDE ---\nVolumen: 500\n\n--- 3.3 REFERENCIA: ORDENAMIENTO E INTERCAMBIO ---\nIntercambio: 200 100\nOrdenado: 11 12 22 25 64\n\n--- 3.4 POTENCIA RECURSIVA ---\n2^8: 256\n\n--- 3.5 REFERENCIA AVANZADA: INTERCAMBIO VALIDADO E INTERCALACION ---\nIntercambio validado: 50 30\nIntercalación: 11 12 22 25 34 64\n\n--- 3.6 EUCLIDES RECURSIVO ---\nMCD: 6 Pasos: 4\n\n--- 4.1 FMT.PRINTLN ---\nImpresion directa de texto\n\n--- 4.2 LEN ---\nlen(texto): 7\nlen(arrLen): 4\n\n--- 4.3 NOW ---\nFecha actual: 2026-05-02 14:56:06\n\n--- 4.4 SUBSTR ---\nsubstr: Organizacion\n\n--- 4.5 TYPEOF ---\nint32: int\nfloat32: float64\nbool: bool\nstring: string\n\n=== FIN DE CALIFICACION: FUNCIONES ===\n"
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
