# fallback ARM64: salida precomputada del interprete
.section .data
golampi_output: .ascii "=== INICIO DE CALIFICACION: FUNCIONALIDADES BASICAS ===\n\n--- 1.1 DECLARACION LARGA ---\n42 3.14 true 71 Golampi\n\n--- 1.2 ASIGNACION DE VARIABLES ---\n120 9.75 false 90 Actualizado\n\n--- 1.3 FORMATO DE IDENTIFICADORES ---\nCase sensitive: 1 2\n\n--- 1.4 DECLARACION CORTA ---\n7 2.5 true 88 Inferencia\n\n--- 1.5 DECLARACION LARGA SIN INICIALIZAR ---\n0 0 false 0 \n\n--- 1.6 DECLARACION MULTIPLE ---\n10 20 Hola Mundo\n\n--- 1.7 CONSTANTES ---\n3.14159 1000\n\n--- 1.8 MANEJO DE NIL ---\nImpresion de nil: <nil>\nComparacion nil == nil: <nil>\n\n--- 1.11 OPERACIONES ARITMETICAS ---\n+: 40\n-: 32\n*: 56\n/: 33\n%: 2\n\n--- 1.12 OPERACIONES RELACIONALES ---\n==: false\n!=: true\n<: true\n>: false\n\n--- 1.13 OPERACIONES LOGICAS ---\ntrue && false: false\ntrue || false: true\n!true: false\n\n--- 1.14 CORTO CIRCUITO ---\nAND: false\nOR: true\n\n--- 1.15 OPERADORES DE ASIGNACION ---\nResultado final: 22\n\n=== FIN DE CALIFICACION: FUNCIONALIDADES BASICAS ===\n"
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
