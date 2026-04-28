.section .text
.align 2
.global _start
_start:
stp x29, x30, [sp, #-16]!
stp x19, x20, [sp, #-16]!
mov x29, sp
sub sp, sp, #2048
adrp x0, __str_LIT_0
add x0, x0, :lo12:__str_LIT_0
ldr x1, =__str_LIT_0_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
ldr x2, =__newline_str_len
mov x8, #64
svc #0
adrp x0, __str_LIT_1
add x0, x0, :lo12:__str_LIT_1
ldr x1, =__str_LIT_1_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
ldr x2, =__newline_str_len
mov x8, #64
svc #0
bl funcionHoisting
adrp x0, __str_LIT_2
add x0, x0, :lo12:__str_LIT_2
ldr x1, =__str_LIT_2_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
ldr x2, =__newline_str_len
mov x8, #64
svc #0
bl mostrarBienvenida
adrp x0, __str_LIT_3
add x0, x0, :lo12:__str_LIT_3
ldr x1, =__str_LIT_3_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
ldr x2, =__newline_str_len
mov x8, #64
svc #0
mov x0, #15
mov x20, x0
mov x0, #25
mov x21, x0
mov x0, x20
mov x1, x21
bl sumarNumeros
str x0, [sp, #0]
adrp x0, __str_LIT_4
add x0, x0, :lo12:__str_LIT_4
ldr x1, =__str_LIT_4_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
ldr x0, [sp, #0]
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
ldr x2, =__newline_str_len
mov x8, #64
svc #0
adrp x0, __str_LIT_5
add x0, x0, :lo12:__str_LIT_5
ldr x1, =__str_LIT_5_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
ldr x2, =__newline_str_len
mov x8, #64
svc #0
mov x0, #10
str x0, [sp, #8]
adrp x0, __str_LIT_6
add x0, x0, :lo12:__str_LIT_6
ldr x1, =__str_LIT_6_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
ldr x0, [sp, #8]
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
ldr x2, =__newline_str_len
mov x8, #64
svc #0
add x0, sp, #8
mov x0, x0
bl duplicarPorReferencia
adrp x0, __str_LIT_7
add x0, x0, :lo12:__str_LIT_7
ldr x1, =__str_LIT_7_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
ldr x0, [sp, #8]
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
ldr x2, =__newline_str_len
mov x8, #64
svc #0
adrp x0, __str_LIT_8
add x0, x0, :lo12:__str_LIT_8
ldr x1, =__str_LIT_8_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
ldr x2, =__newline_str_len
mov x8, #64
svc #0
mov x0, #60
str x0, [sp, #16]
mov x0, #75
str x0, [sp, #24]
mov x0, #82
str x0, [sp, #32]
mov x0, #90
str x0, [sp, #40]
adrp x0, __str_LIT_9
add x0, x0, :lo12:__str_LIT_9
ldr x1, =__str_LIT_9_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
ldr x2, =__newline_str_len
mov x8, #64
svc #0
adrp x0, __str_LIT_10
add x0, x0, :lo12:__str_LIT_10
ldr x1, =__str_LIT_10_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
mov x0, #0
mov x9, x0
add x11, sp, #16
lsl x9, x9, #3
add x11, x11, x9
ldr x0, [x11]
bl __print_int
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
adrp x0, __str_LIT_11
add x0, x0, :lo12:__str_LIT_11
ldr x1, =__str_LIT_11_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
mov x0, #1
mov x9, x0
add x11, sp, #16
lsl x9, x9, #3
add x11, x11, x9
ldr x0, [x11]
bl __print_int
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
adrp x0, __str_LIT_12
add x0, x0, :lo12:__str_LIT_12
ldr x1, =__str_LIT_12_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
mov x0, #2
mov x9, x0
add x11, sp, #16
lsl x9, x9, #3
add x11, x11, x9
ldr x0, [x11]
bl __print_int
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
adrp x0, __str_LIT_13
add x0, x0, :lo12:__str_LIT_13
ldr x1, =__str_LIT_13_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
mov x0, #3
mov x9, x0
add x11, sp, #16
lsl x9, x9, #3
add x11, x11, x9
ldr x0, [x11]
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
ldr x2, =__newline_str_len
mov x8, #64
svc #0
mov x0, #5
mov x20, x0
add x0, sp, #16
mov x0, x0
mov x1, #4
mov x1, x20
bl bonificarNotas
adrp x0, __str_LIT_14
add x0, x0, :lo12:__str_LIT_14
ldr x1, =__str_LIT_14_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
ldr x2, =__newline_str_len
mov x8, #64
svc #0
adrp x0, __str_LIT_10
add x0, x0, :lo12:__str_LIT_10
ldr x1, =__str_LIT_10_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
mov x0, #0
mov x9, x0
add x11, sp, #16
lsl x9, x9, #3
add x11, x11, x9
ldr x0, [x11]
bl __print_int
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
adrp x0, __str_LIT_11
add x0, x0, :lo12:__str_LIT_11
ldr x1, =__str_LIT_11_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
mov x0, #1
mov x9, x0
add x11, sp, #16
lsl x9, x9, #3
add x11, x11, x9
ldr x0, [x11]
bl __print_int
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
adrp x0, __str_LIT_12
add x0, x0, :lo12:__str_LIT_12
ldr x1, =__str_LIT_12_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
mov x0, #2
mov x9, x0
add x11, sp, #16
lsl x9, x9, #3
add x11, x11, x9
ldr x0, [x11]
bl __print_int
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
adrp x0, __str_LIT_13
add x0, x0, :lo12:__str_LIT_13
ldr x1, =__str_LIT_13_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
mov x0, #3
mov x9, x0
add x11, sp, #16
lsl x9, x9, #3
add x11, x11, x9
ldr x0, [x11]
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
ldr x2, =__newline_str_len
mov x8, #64
svc #0
adrp x0, __str_LIT_15
add x0, x0, :lo12:__str_LIT_15
ldr x1, =__str_LIT_15_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
ldr x2, =__newline_str_len
mov x8, #64
svc #0
mov x0, #50
mov x20, x0
mov x0, #20
mov x21, x0
mov x0, x20
mov x1, x21
bl operacionesBasicas
mov x9, x0
str x9, [sp, #48]
mov x9, x1
str x9, [sp, #56]
adrp x0, __str_LIT_16
add x0, x0, :lo12:__str_LIT_16
ldr x1, =__str_LIT_16_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
ldr x0, [sp, #48]
bl __print_int
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
adrp x0, __str_LIT_17
add x0, x0, :lo12:__str_LIT_17
ldr x1, =__str_LIT_17_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
ldr x0, [sp, #56]
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
ldr x2, =__newline_str_len
mov x8, #64
svc #0
adrp x0, __str_LIT_18
add x0, x0, :lo12:__str_LIT_18
ldr x1, =__str_LIT_18_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
ldr x2, =__newline_str_len
mov x8, #64
svc #0
mov x0, #5
mov x20, x0
mov x0, x20
bl factorial
str x0, [sp, #64]
adrp x0, __str_LIT_19
add x0, x0, :lo12:__str_LIT_19
ldr x1, =__str_LIT_19_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
ldr x0, [sp, #64]
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
ldr x2, =__newline_str_len
mov x8, #64
svc #0
adrp x0, __str_LIT_20
add x0, x0, :lo12:__str_LIT_20
ldr x1, =__str_LIT_20_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
ldr x2, =__newline_str_len
mov x8, #64
svc #0
mov x0, #4
mov x20, x0
mov x0, x20
bl fibonacciAvanzado
mov x9, x0
str x9, [sp, #72]
mov x9, x1
str x9, [sp, #80]
adrp x0, __str_LIT_21
add x0, x0, :lo12:__str_LIT_21
ldr x1, =__str_LIT_21_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
ldr x0, [sp, #72]
bl __print_int
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
adrp x0, __str_LIT_22
add x0, x0, :lo12:__str_LIT_22
ldr x1, =__str_LIT_22_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
ldr x2, =__space_str_len
mov x8, #64
svc #0
ldr x0, [sp, #80]
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
ldr x2, =__newline_str_len
mov x8, #64
svc #0
adrp x0, __str_LIT_23
add x0, x0, :lo12:__str_LIT_23
ldr x1, =__str_LIT_23_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
ldr x2, =__newline_str_len
mov x8, #64
svc #0
_start_end:
add sp, sp, #2048
ldp x19, x20, [sp], #16
ldp x29, x30, [sp], #16
# exit(0)
mov x0, #0
mov x8, #93
svc #0
bonificarNotas:
stp x29, x30, [sp, #-16]!
stp x19, x20, [sp, #-16]!
mov x29, sp
sub sp, sp, #2048
str x0, [sp, #0]
str x1, [sp, #8]
mov x0, #0
str x0, [sp, #16]
L_FOR_CLASSIC_24:
ldr x0, [sp, #16]
mov x19, x0
mov x0, #4
cmp x19, x0
cset x0, lt
cmp x0, #0
b.eq L_FOR_END_26
ldr x0, [sp, #16]
mov x14, x0
ldr x0, [sp, #16]
mov x9, x0
ldr x11, [sp, #0]
lsl x9, x9, #3
add x11, x11, x9
ldr x0, [x11]
mov x19, x0
ldr x0, [sp, #8]
mov x20, x0
add x0, x19, x20
ldr x11, [sp, #0]
lsl x14, x14, #3
add x11, x11, x14
str x0, [x11]
L_FOR_POST_25:
ldr x0, [sp, #16]
add x0, x0, #1
str x0, [sp, #16]
b L_FOR_CLASSIC_24
L_FOR_END_26:
bonificarNotas_end:
add sp, sp, #2048
ldp x19, x20, [sp], #16
ldp x29, x30, [sp], #16
ret
duplicarPorReferencia:
stp x29, x30, [sp, #-16]!
stp x19, x20, [sp, #-16]!
mov x29, sp
sub sp, sp, #2048
str x0, [sp, #0]
ldr x11, [sp, #0]
ldr x0, [x11]
mov x19, x0
mov x0, #2
mov x20, x0
mul x0, x19, x20
ldr x11, [sp, #0]
str x0, [x11]
duplicarPorReferencia_end:
add sp, sp, #2048
ldp x19, x20, [sp], #16
ldp x29, x30, [sp], #16
ret
factorial:
stp x29, x30, [sp, #-16]!
stp x19, x20, [sp, #-16]!
mov x29, sp
sub sp, sp, #2048
str x0, [sp, #0]
ldr x0, [sp, #0]
mov x19, x0
mov x0, #1
cmp x19, x0
cset x0, le
cmp x0, #0
b.eq L_ENDIF_28
mov x0, #1
b factorial_end
L_ENDIF_28:
ldr x0, [sp, #0]
mov x19, x0
ldr x0, [sp, #0]
mov x19, x0
mov x0, #1
mov x20, x0
sub x0, x19, x20
mov x20, x0
mov x0, x20
bl factorial
mov x20, x0
mul x0, x19, x20
b factorial_end
factorial_end:
add sp, sp, #2048
ldp x19, x20, [sp], #16
ldp x29, x30, [sp], #16
ret
fibonacciAvanzado:
stp x29, x30, [sp, #-16]!
stp x19, x20, [sp, #-16]!
mov x29, sp
sub sp, sp, #2048
str x0, [sp, #0]
ldr x0, [sp, #0]
mov x19, x0
mov x0, #1
cmp x19, x0
cset x0, le
cmp x0, #0
b.eq L_ENDIF_30
ldr x0, [sp, #0]
mov x9, x0
mov x0, #1
mov x1, x0
mov x0, x9
b fibonacciAvanzado_end
L_ENDIF_30:
ldr x0, [sp, #0]
mov x19, x0
mov x0, #1
mov x20, x0
sub x0, x19, x20
mov x20, x0
mov x0, x20
bl fibonacciAvanzado
mov x9, x0
str x9, [sp, #8]
mov x9, x1
str x9, [sp, #16]
ldr x0, [sp, #0]
mov x19, x0
mov x0, #2
mov x20, x0
sub x0, x19, x20
mov x20, x0
mov x0, x20
bl fibonacciAvanzado
mov x9, x0
str x9, [sp, #24]
mov x9, x1
str x9, [sp, #32]
ldr x0, [sp, #8]
mov x19, x0
ldr x0, [sp, #24]
mov x20, x0
add x0, x19, x20
str x0, [sp, #40]
ldr x0, [sp, #16]
mov x19, x0
ldr x0, [sp, #32]
mov x20, x0
add x0, x19, x20
mov x19, x0
mov x0, #1
mov x20, x0
add x0, x19, x20
str x0, [sp, #48]
ldr x0, [sp, #40]
mov x9, x0
ldr x0, [sp, #48]
mov x1, x0
mov x0, x9
b fibonacciAvanzado_end
fibonacciAvanzado_end:
add sp, sp, #2048
ldp x19, x20, [sp], #16
ldp x29, x30, [sp], #16
ret
funcionHoisting:
stp x29, x30, [sp, #-16]!
stp x19, x20, [sp, #-16]!
mov x29, sp
sub sp, sp, #2048
adrp x0, __str_LIT_31
add x0, x0, :lo12:__str_LIT_31
ldr x1, =__str_LIT_31_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
ldr x2, =__newline_str_len
mov x8, #64
svc #0
funcionHoisting_end:
add sp, sp, #2048
ldp x19, x20, [sp], #16
ldp x29, x30, [sp], #16
ret
mostrarBienvenida:
stp x29, x30, [sp, #-16]!
stp x19, x20, [sp, #-16]!
mov x29, sp
sub sp, sp, #2048
adrp x0, __str_LIT_32
add x0, x0, :lo12:__str_LIT_32
ldr x1, =__str_LIT_32_len
mov x3, x0
mov x4, x1
mov x0, #1
mov x1, x3
mov x2, x4
mov x8, #64
svc #0
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
ldr x2, =__newline_str_len
mov x8, #64
svc #0
mostrarBienvenida_end:
add sp, sp, #2048
ldp x19, x20, [sp], #16
ldp x29, x30, [sp], #16
ret
operacionesBasicas:
stp x29, x30, [sp, #-16]!
stp x19, x20, [sp, #-16]!
mov x29, sp
sub sp, sp, #2048
str x0, [sp, #0]
str x1, [sp, #8]
ldr x0, [sp, #0]
mov x19, x0
ldr x0, [sp, #8]
mov x20, x0
add x0, x19, x20
str x0, [sp, #16]
ldr x0, [sp, #0]
mov x19, x0
ldr x0, [sp, #8]
mov x20, x0
sub x0, x19, x20
str x0, [sp, #24]
ldr x0, [sp, #16]
mov x9, x0
ldr x0, [sp, #24]
mov x1, x0
mov x0, x9
b operacionesBasicas_end
operacionesBasicas_end:
add sp, sp, #2048
ldp x19, x20, [sp], #16
ldp x29, x30, [sp], #16
ret
sumarNumeros:
stp x29, x30, [sp, #-16]!
stp x19, x20, [sp, #-16]!
mov x29, sp
sub sp, sp, #2048
str x0, [sp, #0]
str x1, [sp, #8]
ldr x0, [sp, #0]
mov x19, x0
ldr x0, [sp, #8]
mov x20, x0
add x0, x19, x20
b sumarNumeros_end
sumarNumeros_end:
add sp, sp, #2048
ldp x19, x20, [sp], #16
ldp x29, x30, [sp], #16
ret
__print_int:
stp x29, x30, [sp, #-16]!
mov x29, sp
sub sp, sp, #32
mov x9, x0
adrp x10, __int_buffer
add x10, x10, :lo12:__int_buffer
add x11, x10, #31
mov w12, #0
strb w12, [x11]
mov x13, #0
cmp x9, #0
b.ge L_PI_POS_35
neg x9, x9
mov x13, #1
L_PI_POS_35:
cmp x9, #0
b.ne L_PI_LOOP_33
sub x11, x11, #1
mov w12, #48
strb w12, [x11]
b L_PI_AFTER_DIGITS_34
L_PI_LOOP_33:
mov x15, #10
udiv x14, x9, x15
msub x16, x14, x15, x9
add x16, x16, #48
sub x11, x11, #1
strb w16, [x11]
mov x9, x14
cmp x9, #0
b.ne L_PI_LOOP_33
L_PI_AFTER_DIGITS_34:
cmp x13, #0
b.eq L_PI_NOSIGN_36
sub x11, x11, #1
mov w12, #45
strb w12, [x11]
L_PI_NOSIGN_36:
add x17, x10, #31
sub x2, x17, x11
mov x0, #1
mov x1, x11
mov x8, #64
svc #0
add sp, sp, #32
ldp x29, x30, [sp], #16
ret
.section .bss
__int_buffer: .skip 32
.section .data
__newline_str: .ascii "\n"
__newline_str_len = . - __newline_str
__space_str: .ascii " "
__space_str_len = . - __space_str
__true_str: .ascii "true"
__true_str_len = . - __true_str
__false_str: .ascii "false"
__false_str_len = . - __false_str
__empty_str: .ascii ""
__empty_str_len = . - __empty_str
__str_LIT_0: .ascii "=== INICIO DE CALIFICACION: FUNCIONES ==="
__str_LIT_0_len = . - __str_LIT_0
__str_LIT_1: .ascii "\n--- 3.7 HOISTING ---"
__str_LIT_1_len = . - __str_LIT_1
__str_LIT_2: .ascii "\n--- 3.1 FUNCION SIN PARAMETROS ---"
__str_LIT_2_len = . - __str_LIT_2
__str_LIT_3: .ascii "\n--- 3.2 FUNCION CON PARAMETROS ---"
__str_LIT_3_len = . - __str_LIT_3
__str_LIT_4: .ascii "Resultado de sumarNumeros(15, 25):"
__str_LIT_4_len = . - __str_LIT_4
__str_LIT_5: .ascii "\n--- 3.3 FUNCIONES POR REFERENCIA ---"
__str_LIT_5_len = . - __str_LIT_5
__str_LIT_6: .ascii "Valor antes de la función por referencia:"
__str_LIT_6_len = . - __str_LIT_6
__str_LIT_7: .ascii "Valor después de la función por referencia (debe ser 20):"
__str_LIT_7_len = . - __str_LIT_7
__str_LIT_8: .ascii "\n--- 3.3 FUNCIONES POR REFERENCIA (CON ARREGLOS) ---"
__str_LIT_8_len = . - __str_LIT_8
__str_LIT_9: .ascii "Notas ANTES de la bonificación:"
__str_LIT_9_len = . - __str_LIT_9
__str_LIT_10: .ascii "Nota 1:"
__str_LIT_10_len = . - __str_LIT_10
__str_LIT_11: .ascii "| Nota 2:"
__str_LIT_11_len = . - __str_LIT_11
__str_LIT_12: .ascii "| Nota 3:"
__str_LIT_12_len = . - __str_LIT_12
__str_LIT_13: .ascii "| Nota 4:"
__str_LIT_13_len = . - __str_LIT_13
__str_LIT_14: .ascii "\nNotas DESPUES de la bonificación (+5 puntos):"
__str_LIT_14_len = . - __str_LIT_14
__str_LIT_15: .ascii "\n--- 3.4 FUNCION MULTIPLE RETORNO ---"
__str_LIT_15_len = . - __str_LIT_15
__str_LIT_16: .ascii "Operaciones 50 y 20 -> Suma:"
__str_LIT_16_len = . - __str_LIT_16
__str_LIT_17: .ascii ", Resta:"
__str_LIT_17_len = . - __str_LIT_17
__str_LIT_18: .ascii "\n--- 3.5 FUNCION RECURSIVA (UN RETORNO) ---"
__str_LIT_18_len = . - __str_LIT_18
__str_LIT_19: .ascii "Factorial de 5:"
__str_LIT_19_len = . - __str_LIT_19
__str_LIT_20: .ascii "\n--- 3.6 FUNCION RECURSIVA (MULTIPLE RETORNO) ---"
__str_LIT_20_len = . - __str_LIT_20
__str_LIT_21: .ascii "Fibonacci de 4:"
__str_LIT_21_len = . - __str_LIT_21
__str_LIT_22: .ascii "| Llamadas recursivas totales:"
__str_LIT_22_len = . - __str_LIT_22
__str_LIT_23: .ascii "\n=== FIN DE CALIFICACION: FUNCIONES ==="
__str_LIT_23_len = . - __str_LIT_23
__str_LIT_31: .ascii ">>> Ejecutando funcionHoisting() declarada correctamente debajo del main."
__str_LIT_31_len = . - __str_LIT_31
__str_LIT_32: .ascii "¡Bienvenido al sistema de evaluación de funciones Golampi!"
__str_LIT_32_len = . - __str_LIT_32
