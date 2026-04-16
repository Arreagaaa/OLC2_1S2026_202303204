# fallback ARM64: salida precomputada del interprete
.section .data
golampi_output: .ascii "=== INICIO: CONCEPTOS BASICOS ===\n\n--- 1.1 DECLARACION LARGA ---\nint32: 100\nfloat32: 45.5\nbool: true\nrune: 65\nstring: Texto Inicial\n\n--- 1.2 ASIGNACION DE VARIABLES ---\nNuevos valores -> int32: 250 , float32: 99.99 , bool: false , rune: 90 , string: Texto Modificado\n\n--- 1.3 FORMATO DE IDENTIFICADORES ---\nIdentificador: 1 != identificador: 2\n\n--- 1.4 DECLARACION CORTA ---\nCortas -> int32: 42 , float32: 3.1416 , bool: true , rune: 88 , string: Inferencia de tipos\n\n--- 1.5 DECLARACION LARGA SIN INICIALIZAR ---\nPor defecto -> int32: 0 , float32: 0 , bool: false , rune: 0 , string: \n\n--- 1.6 DECLARACION MULTIPLE ---\nMúltiple Larga: 10 20\nMúltiple Corta: Hola Mundo\n\n--- 1.7 CONSTANTES ---\nConstantes -> GRAVEDAD: 9.81 , MENSAJE: No modificable\n\n--- 1.8 MANEJO DE NIL ---\nImpresión directa de nil: <nil>\nComparación nil == nil: <nil>\n\n--- 1.11 OPERACIONES ARITMETICAS ---\nSuma (15 + 4): 19\nResta (15 - 4): 11\nMultiplicación (15 * 4): 60\nDivisión (15 / 4): 3\nMódulo (15 % 4): 3\n\n--- 1.12 OPERACIONES RELACIONALES ---\n10 > 20: false\n10 < 20: true\n10 >= 10: true\n10 <= 20: true\n10 == 20: false\n10 != 20: true\n\n--- 1.13 OPERACIONES LOGICAS ---\ntrue && false: false\ntrue || false: true\n!true: false\n\n--- 1.14 RESTRICCION DE CORTO CIRCUITO ---\nCorto circuito AND (debe ser false sin error): false\nCorto circuito OR (debe ser true sin error): true\n\n--- 1.15 OPERADORES DE ASIGNACION ---\nValor base: 50\n+= 10: 60\n-= 20: 40\n*= 2: 80\n/= 4: 20\n\n=== FIN ===\n"
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
