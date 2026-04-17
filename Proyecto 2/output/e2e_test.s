.section .text
.align 2
.global _start
_start:
stp x29, x30, [sp, #-16]!
mov x29, sp
sub sp, sp, #2048
str xzr, [sp, #0]
str xzr, [sp, #8]
str xzr, [sp, #16]
mov x0, #0
mov x9, x0
mov x0, #10
add x11, sp, #0
lsl x9, x9, #3
add x11, x11, x9
str x0, [x11]
mov x0, #1
mov x9, x0
mov x0, #20
add x11, sp, #0
lsl x9, x9, #3
add x11, x11, x9
str x0, [x11]
mov x0, #2
mov x9, x0
mov x0, #30
add x11, sp, #0
lsl x9, x9, #3
add x11, x11, x9
str x0, [x11]
mov x0, #0
mov x9, x0
add x11, sp, #0
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
mov x0, #1
mov x9, x0
add x11, sp, #0
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
mov x0, #2
mov x9, x0
add x11, sp, #0
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
mov x0, #1
str x0, [sp, #24]
mov x0, #2
str x0, [sp, #32]
mov x0, #3
str x0, [sp, #40]
mov x0, #4
str x0, [sp, #48]
mov x0, #1
mov x9, x0
mov x0, #1
mov x10, x0
mov x0, #9
mov x12, #2
mul x9, x9, x12
add x9, x9, x10
add x11, sp, #24
lsl x9, x9, #3
add x11, x11, x9
str x0, [x11]
mov x0, #0
mov x9, x0
mov x0, #1
mov x10, x0
mov x12, #2
mul x9, x9, x12
add x9, x9, x10
add x11, sp, #24
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
mov x0, #1
mov x9, x0
mov x0, #0
mov x10, x0
mov x12, #2
mul x9, x9, x12
add x9, x9, x10
add x11, sp, #24
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
mov x0, #1
mov x9, x0
mov x0, #1
mov x10, x0
mov x12, #2
mul x9, x9, x12
add x9, x9, x10
add x11, sp, #24
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
_start_end:
add sp, sp, #2048
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
b.ge L_PI_POS_2
neg x9, x9
mov x13, #1
L_PI_POS_2:
cmp x9, #0
b.ne L_PI_LOOP_0
sub x11, x11, #1
mov w12, #48
strb w12, [x11]
b L_PI_AFTER_DIGITS_1
L_PI_LOOP_0:
mov x15, #10
udiv x14, x9, x15
msub x16, x14, x15, x9
add x16, x16, #48
sub x11, x11, #1
strb w16, [x11]
mov x9, x14
cmp x9, #0
b.ne L_PI_LOOP_0
L_PI_AFTER_DIGITS_1:
cmp x13, #0
b.eq L_PI_NOSIGN_3
sub x11, x11, #1
mov w12, #45
strb w12, [x11]
L_PI_NOSIGN_3:
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
