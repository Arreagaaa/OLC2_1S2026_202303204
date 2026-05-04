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
mov x1, #40
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
mov x2, #1
mov x8, #64
svc #0
adrp x0, __str_LIT_1
add x0, x0, :lo12:__str_LIT_1
mov x1, #41
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
mov x2, #1
mov x8, #64
svc #0
str xzr, [sp, #0]
str xzr, [sp, #8]
str xzr, [sp, #16]
str xzr, [sp, #24]
mov x0, #1
str x0, [sp, #32]
mov x0, #2
str x0, [sp, #40]
mov x0, #3
str x0, [sp, #48]
mov x0, #4
str x0, [sp, #56]
adrp x0, __str_LIT_2
add x0, x0, :lo12:__str_LIT_2
mov x1, #30
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
mov x2, #1
mov x8, #64
svc #0
mov x0, #1
mov x9, x0
mov x0, #1
mov x10, x0
mov x12, #2
mul x9, x9, x12
add x9, x9, x10
add x11, sp, #0
lsl x9, x9, #3
add x11, x11, x9
ldr x0, [x11]
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
adrp x0, __str_LIT_3
add x0, x0, :lo12:__str_LIT_3
mov x1, #27
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
mov x2, #1
mov x8, #64
svc #0
mov x0, #0
mov x9, x0
mov x0, #0
mov x10, x0
mov x12, #2
mul x9, x9, x12
add x9, x9, x10
add x11, sp, #32
lsl x9, x9, #3
add x11, x11, x9
ldr x0, [x11]
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
adrp x0, __str_LIT_4
add x0, x0, :lo12:__str_LIT_4
mov x1, #51
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
mov x2, #1
mov x8, #64
svc #0
adrp x0, __str_LIT_5
add x0, x0, :lo12:__str_LIT_5
mov x1, #28
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
mov x2, #1
mov x8, #64
svc #0
mov x0, #0
mov x9, x0
mov x0, #1
mov x10, x0
mov x12, #2
mul x9, x9, x12
add x9, x9, x10
add x11, sp, #0
lsl x9, x9, #3
add x11, x11, x9
ldr x0, [x11]
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
mov x0, #0
mov x14, x0
mov x0, #1
mov x15, x0
mov x0, #77
mov x12, #2
mul x14, x14, x12
add x14, x14, x15
add x11, sp, #0
lsl x14, x14, #3
add x11, x11, x14
str x0, [x11]
adrp x0, __str_LIT_6
add x0, x0, :lo12:__str_LIT_6
mov x1, #30
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
mov x2, #1
mov x8, #64
svc #0
mov x0, #0
mov x9, x0
mov x0, #1
mov x10, x0
mov x12, #2
mul x9, x9, x12
add x9, x9, x10
add x11, sp, #0
lsl x9, x9, #3
add x11, x11, x9
ldr x0, [x11]
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
adrp x0, __str_LIT_7
add x0, x0, :lo12:__str_LIT_7
mov x1, #38
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
mov x2, #1
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
b.ge L_PI_POS_10
neg x9, x9
mov x13, #1
L_PI_POS_10:
cmp x9, #0
b.ne L_PI_LOOP_8
sub x11, x11, #1
mov w12, #48
strb w12, [x11]
b L_PI_AFTER_DIGITS_9
L_PI_LOOP_8:
mov x15, #10
udiv x14, x9, x15
msub x16, x14, x15, x9
add x16, x16, #48
sub x11, x11, #1
strb w16, [x11]
mov x9, x14
cmp x9, #0
b.ne L_PI_LOOP_8
L_PI_AFTER_DIGITS_9:
cmp x13, #0
b.eq L_PI_NOSIGN_11
sub x11, x11, #1
mov w12, #45
strb w12, [x11]
L_PI_NOSIGN_11:
add x17, x10, #31
sub x2, x17, x11
mov x0, #1
mov x1, x11
mov x8, #64
svc #0
add sp, sp, #32
ldp x29, x30, [sp], #16
ret
__print_float_scaled:
stp x29, x30, [sp, #-16]!
mov x29, sp
sub sp, sp, #32
mov x9, x0
ldr x15, =1000000000
cmp x9, #0
b.ge L_PF_POS_12
mov x0, #1
adrp x1, __minus_str
add x1, x1, :lo12:__minus_str
mov x2, #1
mov x8, #64
svc #0
neg x9, x9
b L_PF_AFTER_SIGN_13
L_PF_POS_12:
L_PF_AFTER_SIGN_13:
udiv x10, x9, x15
msub x11, x10, x15, x9
mov x0, x10
mov x20, x11
bl __print_int
ldr x15, =1000000000
mov x11, x20
cmp x11, #0
b.eq L_PF_NO_FRAC_14
mov x0, #1
adrp x1, __dot_str
add x1, x1, :lo12:__dot_str
mov x2, #1
mov x8, #64
svc #0
adrp x12, __frac_buffer
add x12, x12, :lo12:__frac_buffer
mov x13, #0
L_PF_DIGIT_LOOP_15:
cmp x13, #9
b.ge L_PF_DIGIT_DONE_16
mov x14, #10
mul x11, x11, x14
udiv x16, x11, x15
msub x11, x16, x15, x11
add x16, x16, #48
strb w16, [x12, x13]
add x13, x13, #1
b L_PF_DIGIT_LOOP_15
L_PF_DIGIT_DONE_16:
mov x17, #8
L_PF_TRIM_LOOP_17:
ldrb w16, [x12, x17]
cmp w16, #48
b.ne L_PF_TRIM_DONE_18
subs x17, x17, #1
b.pl L_PF_TRIM_LOOP_17
mov x17, #0
L_PF_TRIM_DONE_18:
add x2, x17, #1
mov x0, #1
mov x1, x12
mov x8, #64
svc #0
L_PF_NO_FRAC_14:
add sp, sp, #32
ldp x29, x30, [sp], #16
ret
.section .bss
__int_buffer: .skip 32
__frac_buffer: .skip 16
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
__nil_str: .ascii "<nil>"
__nil_str_len = . - __nil_str
__minus_str: .ascii "-"
__minus_str_len = . - __minus_str
__dot_str: .ascii "."
__dot_str_len = . - __dot_str
__str_LIT_0: .ascii "=== INICIO DE CALIFICACION: ARREGLOS ==="
__str_LIT_0_len = . - __str_LIT_0
__str_LIT_1: .ascii "\n--- 5.1 DECLARACION MULTIDIMENSIONAL ---"
__str_LIT_1_len = . - __str_LIT_1
__str_LIT_2: .ascii "Matriz no inicializada [1][1]:"
__str_LIT_2_len = . - __str_LIT_2
__str_LIT_3: .ascii "Matriz inicializada [0][0]:"
__str_LIT_3_len = . - __str_LIT_3
__str_LIT_4: .ascii "\n--- 5.2 ACCESO Y MODIFICACION MULTIDIMENSIONAL ---"
__str_LIT_4_len = . - __str_LIT_4
__str_LIT_5: .ascii "Original matrizNoInit[0][1]:"
__str_LIT_5_len = . - __str_LIT_5
__str_LIT_6: .ascii "Modificado matrizNoInit[0][1]:"
__str_LIT_6_len = . - __str_LIT_6
__str_LIT_7: .ascii "\n=== FIN DE CALIFICACION: ARREGLOS ==="
__str_LIT_7_len = . - __str_LIT_7
