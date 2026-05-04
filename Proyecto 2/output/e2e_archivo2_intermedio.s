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
mov x1, #54
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
mov x0, #85
str x0, [sp, #0]
mov x0, #92
str x0, [sp, #8]
adrp x0, __str_LIT_1
add x0, x0, :lo12:__str_LIT_1
mov x1, #3
str x0, [sp, #16]
str x1, [sp, #24]
adrp x0, __str_LIT_2
add x0, x0, :lo12:__str_LIT_2
mov x1, #15
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
ldr x0, [sp, #0]
sub sp, sp, #16
str x0, [sp]
mov x0, #80
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
cmp x19, x20
cset x0, gt
cmp x0, #0
b.eq L_ENDIF_4
adrp x0, __str_LIT_5
add x0, x0, :lo12:__str_LIT_5
mov x1, #13
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
ldr x0, [sp, #16]
ldr x1, [sp, #24]
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
adrp x0, __str_LIT_6
add x0, x0, :lo12:__str_LIT_6
mov x1, #25
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
L_ENDIF_4:
adrp x0, __str_LIT_7
add x0, x0, :lo12:__str_LIT_7
mov x1, #20
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
ldr x0, [sp, #8]
sub sp, sp, #16
str x0, [sp]
mov x0, #95
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
cmp x19, x20
cset x0, ge
cmp x0, #0
b.eq L_ELSE_8
adrp x0, __str_LIT_10
add x0, x0, :lo12:__str_LIT_10
mov x1, #28
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
b L_ENDIF_9
L_ELSE_8:
ldr x0, [sp, #8]
sub sp, sp, #16
str x0, [sp]
mov x0, #90
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
cmp x19, x20
cset x0, ge
cmp x0, #0
b.eq L_ELSE_11
adrp x0, __str_LIT_13
add x0, x0, :lo12:__str_LIT_13
mov x1, #24
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
b L_ENDIF_12
L_ELSE_11:
adrp x0, __str_LIT_14
add x0, x0, :lo12:__str_LIT_14
mov x1, #22
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
L_ENDIF_12:
L_ENDIF_9:
adrp x0, __str_LIT_15
add x0, x0, :lo12:__str_LIT_15
mov x1, #32
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
mov x0, #2
str x0, [sp, #32]
ldr x0, [sp, #32]
mov x20, x0
mov x0, #1
cmp x20, x0
b.eq L_CASE_BODY_17
b L_CASE_NEXT_18
L_CASE_BODY_17:
adrp x0, __str_LIT_19
add x0, x0, :lo12:__str_LIT_19
mov x1, #25
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
b L_SWITCH_END_16
L_CASE_NEXT_18:
mov x0, #2
cmp x20, x0
b.eq L_CASE_BODY_20
b L_CASE_NEXT_21
L_CASE_BODY_20:
adrp x0, __str_LIT_22
add x0, x0, :lo12:__str_LIT_22
mov x1, #23
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
b L_SWITCH_END_16
L_CASE_NEXT_21:
mov x0, #3
cmp x20, x0
b.eq L_CASE_BODY_23
b L_CASE_NEXT_24
L_CASE_BODY_23:
adrp x0, __str_LIT_25
add x0, x0, :lo12:__str_LIT_25
mov x1, #21
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
b L_SWITCH_END_16
L_CASE_NEXT_24:
adrp x0, __str_LIT_26
add x0, x0, :lo12:__str_LIT_26
mov x1, #21
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
L_SWITCH_END_16:
adrp x0, __str_LIT_27
add x0, x0, :lo12:__str_LIT_27
mov x1, #24
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
mov x0, #1
str x0, [sp, #40]
L_FOR_CLASSIC_28:
ldr x0, [sp, #40]
sub sp, sp, #16
str x0, [sp]
mov x0, #5
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
cmp x19, x20
cset x0, le
cmp x0, #0
b.eq L_FOR_END_30
adrp x0, __str_LIT_31
add x0, x0, :lo12:__str_LIT_31
mov x1, #10
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
ldr x0, [sp, #40]
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
L_FOR_POST_29:
ldr x0, [sp, #40]
add x0, x0, #1
str x0, [sp, #40]
b L_FOR_CLASSIC_28
L_FOR_END_30:
adrp x0, __str_LIT_32
add x0, x0, :lo12:__str_LIT_32
mov x1, #28
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
mov x0, #10
str x0, [sp, #48]
L_FOR_WHILE_33:
ldr x0, [sp, #48]
sub sp, sp, #16
str x0, [sp]
mov x0, #0
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
cmp x19, x20
cset x0, gt
cmp x0, #0
b.eq L_FOR_END_34
adrp x0, __str_LIT_35
add x0, x0, :lo12:__str_LIT_35
mov x1, #17
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
ldr x0, [sp, #48]
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
ldr x0, [sp, #48]
mov x9, x0
mov x0, #3
mov x10, x0
sub x0, x9, x10
str x0, [sp, #48]
b L_FOR_WHILE_33
L_FOR_END_34:
adrp x0, __str_LIT_36
add x0, x0, :lo12:__str_LIT_36
mov x1, #25
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
mov x0, #0
str x0, [sp, #56]
L_FOR_INF_37:
ldr x0, [sp, #56]
add x0, x0, #1
str x0, [sp, #56]
adrp x0, __str_LIT_39
add x0, x0, :lo12:__str_LIT_39
mov x1, #8
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
ldr x0, [sp, #56]
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
ldr x0, [sp, #56]
sub sp, sp, #16
str x0, [sp]
mov x0, #3
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
cmp x19, x20
cset x0, eq
cmp x0, #0
b.eq L_ENDIF_41
b L_FOR_END_38
L_ENDIF_41:
b L_FOR_INF_37
L_FOR_END_38:
adrp x0, __str_LIT_42
add x0, x0, :lo12:__str_LIT_42
mov x1, #18
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
mov x0, #1
str x0, [sp, #40]
L_FOR_CLASSIC_43:
ldr x0, [sp, #40]
sub sp, sp, #16
str x0, [sp]
mov x0, #20
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
cmp x19, x20
cset x0, le
cmp x0, #0
b.eq L_FOR_END_45
ldr x0, [sp, #40]
sub sp, sp, #16
str x0, [sp]
mov x0, #7
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
cmp x19, x20
cset x0, eq
cmp x0, #0
b.eq L_ENDIF_47
adrp x0, __str_LIT_48
add x0, x0, :lo12:__str_LIT_48
mov x1, #30
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
b L_FOR_END_45
L_ENDIF_47:
ldr x0, [sp, #40]
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
L_FOR_POST_44:
ldr x0, [sp, #40]
add x0, x0, #1
str x0, [sp, #40]
b L_FOR_CLASSIC_43
L_FOR_END_45:
adrp x0, __str_LIT_49
add x0, x0, :lo12:__str_LIT_49
mov x1, #21
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
mov x0, #1
str x0, [sp, #64]
L_FOR_CLASSIC_50:
ldr x0, [sp, #64]
sub sp, sp, #16
str x0, [sp]
mov x0, #6
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
cmp x19, x20
cset x0, le
cmp x0, #0
b.eq L_FOR_END_52
ldr x0, [sp, #64]
sub sp, sp, #16
str x0, [sp]
mov x0, #2
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
sdiv x11, x19, x20
msub x0, x11, x20, x19
sub sp, sp, #16
str x0, [sp]
mov x0, #0
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
cmp x19, x20
cset x0, eq
cmp x0, #0
b.eq L_ENDIF_54
b L_FOR_POST_51
L_ENDIF_54:
adrp x0, __str_LIT_55
add x0, x0, :lo12:__str_LIT_55
mov x1, #6
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
ldr x0, [sp, #64]
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
L_FOR_POST_51:
ldr x0, [sp, #64]
add x0, x0, #1
str x0, [sp, #64]
b L_FOR_CLASSIC_50
L_FOR_END_52:
adrp x0, __str_LIT_56
add x0, x0, :lo12:__str_LIT_56
mov x1, #52
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
b.ge L_PI_POS_59
neg x9, x9
mov x13, #1
L_PI_POS_59:
cmp x9, #0
b.ne L_PI_LOOP_57
sub x11, x11, #1
mov w12, #48
strb w12, [x11]
b L_PI_AFTER_DIGITS_58
L_PI_LOOP_57:
mov x15, #10
udiv x14, x9, x15
msub x16, x14, x15, x9
add x16, x16, #48
sub x11, x11, #1
strb w16, [x11]
mov x9, x14
cmp x9, #0
b.ne L_PI_LOOP_57
L_PI_AFTER_DIGITS_58:
cmp x13, #0
b.eq L_PI_NOSIGN_60
sub x11, x11, #1
mov w12, #45
strb w12, [x11]
L_PI_NOSIGN_60:
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
b.ge L_PF_POS_61
mov x0, #1
adrp x1, __minus_str
add x1, x1, :lo12:__minus_str
mov x2, #1
mov x8, #64
svc #0
neg x9, x9
b L_PF_AFTER_SIGN_62
L_PF_POS_61:
L_PF_AFTER_SIGN_62:
udiv x10, x9, x15
msub x11, x10, x15, x9
mov x0, x10
mov x20, x11
bl __print_int
ldr x15, =1000000000
mov x11, x20
cmp x11, #0
b.eq L_PF_NO_FRAC_63
mov x0, #1
adrp x1, __dot_str
add x1, x1, :lo12:__dot_str
mov x2, #1
mov x8, #64
svc #0
adrp x12, __frac_buffer
add x12, x12, :lo12:__frac_buffer
mov x13, #0
L_PF_DIGIT_LOOP_64:
cmp x13, #9
b.ge L_PF_DIGIT_DONE_65
mov x14, #10
mul x11, x11, x14
udiv x16, x11, x15
msub x11, x16, x15, x11
add x16, x16, #48
strb w16, [x12, x13]
add x13, x13, #1
b L_PF_DIGIT_LOOP_64
L_PF_DIGIT_DONE_65:
mov x17, #8
L_PF_TRIM_LOOP_66:
ldrb w16, [x12, x17]
cmp w16, #48
b.ne L_PF_TRIM_DONE_67
subs x17, x17, #1
b.pl L_PF_TRIM_LOOP_66
mov x17, #0
L_PF_TRIM_DONE_67:
add x2, x17, #1
mov x0, #1
mov x1, x12
mov x8, #64
svc #0
L_PF_NO_FRAC_63:
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
__str_LIT_0: .ascii "=== INICIO DE CALIFICACION: ESTRUCTURAS DE CONTROL ==="
__str_LIT_0_len = . - __str_LIT_0
__str_LIT_1: .ascii "Ana"
__str_LIT_1_len = . - __str_LIT_1
__str_LIT_2: .ascii "\n--- 2.2 IF ---"
__str_LIT_2_len = . - __str_LIT_2
__str_LIT_5: .ascii "El estudiante"
__str_LIT_5_len = . - __str_LIT_5
__str_LIT_6: .ascii "tiene una nota mayor a 80"
__str_LIT_6_len = . - __str_LIT_6
__str_LIT_7: .ascii "\n--- 2.3 IF ELSE ---"
__str_LIT_7_len = . - __str_LIT_7
__str_LIT_10: .ascii "Clasificacion: SOBRESALIENTE"
__str_LIT_10_len = . - __str_LIT_10
__str_LIT_13: .ascii "Clasificacion: EXCELENTE"
__str_LIT_13_len = . - __str_LIT_13
__str_LIT_14: .ascii "Clasificacion: REGULAR"
__str_LIT_14_len = . - __str_LIT_14
__str_LIT_15: .ascii "\n--- 2.4 SWITCH CASE DEFAULT ---"
__str_LIT_15_len = . - __str_LIT_15
__str_LIT_19: .ascii "Categoria 1: Principiante"
__str_LIT_19_len = . - __str_LIT_19
__str_LIT_22: .ascii "Categoria 2: Intermedio"
__str_LIT_22_len = . - __str_LIT_22
__str_LIT_25: .ascii "Categoria 3: Avanzado"
__str_LIT_25_len = . - __str_LIT_25
__str_LIT_26: .ascii "Categoria Desconocida"
__str_LIT_26_len = . - __str_LIT_26
__str_LIT_27: .ascii "\n--- 2.5 FOR CLASICO ---"
__str_LIT_27_len = . - __str_LIT_27
__str_LIT_31: .ascii "Iteracion:"
__str_LIT_31_len = . - __str_LIT_31
__str_LIT_32: .ascii "\n--- 2.6 FOR CONDICIONAL ---"
__str_LIT_32_len = . - __str_LIT_32
__str_LIT_35: .ascii "Cuenta regresiva:"
__str_LIT_35_len = . - __str_LIT_35
__str_LIT_36: .ascii "\n--- 2.7 FOR INFINITO ---"
__str_LIT_36_len = . - __str_LIT_36
__str_LIT_39: .ascii "Intento:"
__str_LIT_39_len = . - __str_LIT_39
__str_LIT_42: .ascii "\n--- 2.8 BREAK ---"
__str_LIT_42_len = . - __str_LIT_42
__str_LIT_48: .ascii "Se encontro 7, se aplica break"
__str_LIT_48_len = . - __str_LIT_48
__str_LIT_49: .ascii "\n--- 2.9 CONTINUE ---"
__str_LIT_49_len = . - __str_LIT_49
__str_LIT_55: .ascii "Impar:"
__str_LIT_55_len = . - __str_LIT_55
__str_LIT_56: .ascii "\n=== FIN DE CALIFICACION: ESTRUCTURAS DE CONTROL ==="
__str_LIT_56_len = . - __str_LIT_56
