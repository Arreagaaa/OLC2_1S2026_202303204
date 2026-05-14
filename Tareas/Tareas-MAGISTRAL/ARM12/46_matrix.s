.global _start

.extern itoa

.data
matrix:
    .word 25, 11
    .word 10, 20

.bss
buffer: .space 11

.text
_start:
    ldr x10, =matrix    // load matrix address

    
    mov x0, #0    
    mov x1, #0    
    mov x2, #2    
    bl offset
    mov x11, x0   // x11 = offset matrix[0,0]

   
    mov x0, #1    
    mov x1, #1    
    mov x2, #2    
    bl offset
    mov x12, x0   // x12 = offset [1,1]

    // Cargar valores con offset
    add x11, x10, x11    // load values with offset
    ldr w11, [x11]       // load 32 bits
    add x12, x10, x12    
    ldr w12, [x12]       // load 32 bits
    mul x13, x11, x12    // x13 = x11 * x12  (a)  

    // Calcular offset matrix[1, 0]
    mov x0, #1    // row
    mov x1, #0    // col
    mov x2, #2    // columns
    bl offset
    mov x11, x0   // x11 = offset [1,0]

    // Calcular offset matrix[0, 1]
    mov x0, #0    // row
    mov x1, #1    // col
    mov x2, #2    // columns
    bl offset
    mov x12, x0   // x12 = offset [0,1]

    // Cargar valores con offset
    add x11, x10, x11    // load values wiht offset
    ldr w11, [x11]       // load 32 bits
    add x12, x10, x12    
    ldr w12, [x12]       // load 32 bits
    mul x14, x11, x12    // x14 = x11 * x12 (b)

    sub x11, x13, x14     // x11 = x13 - x14 (det = a -b)


    // Convertir a string
    mov x0, x11          // número a convertir
    ldr x1, =buffer      // buffer para el string
    bl itoa              // llamar a itoa

    // Imprimir resultado
    mov x0, #1           // stdout
    ldr x1, =buffer      // buffer
    mov x2, #11          // tamaño máximo
    mov x8, #64          // syscall write
    svc #0

    // Salir
    mov x8, #93          // syscall exit
    svc #0

offset:
    mul x3, x0, x2       // row * columns
    add x3, x3, x1       // + col
    mov x4, #4           // tamaño de word (4 bytes)
    mul x0, x3, x4       // offset en bytes
    ret
    