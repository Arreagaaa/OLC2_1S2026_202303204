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
mov x1, #55
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
mov x0, #42
str x0, [sp, #0]
ldr x0, =3140000000
str x0, [sp, #8]
mov x0, #1
str x0, [sp, #16]
mov x0, #71
str x0, [sp, #24]
adrp x0, __str_LIT_2
add x0, x0, :lo12:__str_LIT_2
mov x1, #7
str x0, [sp, #32]
str x1, [sp, #40]
ldr x0, [sp, #0]
bl __print_int
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
mov x2, #1
mov x8, #64
svc #0
ldr x0, [sp, #8]
bl __print_float_scaled
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
mov x2, #1
mov x8, #64
svc #0
ldr x0, [sp, #16]
cmp x0, #0
b.ne L_BOOL_TRUE_3
mov x0, #1
adrp x1, __false_str
add x1, x1, :lo12:__false_str
mov x2, #5
mov x8, #64
svc #0
b L_BOOL_END_4
L_BOOL_TRUE_3:
mov x0, #1
adrp x1, __true_str
add x1, x1, :lo12:__true_str
mov x2, #4
mov x8, #64
svc #0
L_BOOL_END_4:
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
mov x2, #1
mov x8, #64
svc #0
ldr x0, [sp, #24]
bl __print_int
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
mov x2, #1
mov x8, #64
svc #0
ldr x0, [sp, #32]
ldr x1, [sp, #40]
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
mov x1, #36
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
mov x0, #120
str x0, [sp, #0]
ldr x0, =9750000000
str x0, [sp, #8]
mov x0, #0
str x0, [sp, #16]
mov x0, #90
str x0, [sp, #24]
adrp x0, __str_LIT_6
add x0, x0, :lo12:__str_LIT_6
mov x1, #11
str x0, [sp, #32]
str x1, [sp, #40]
ldr x0, [sp, #0]
bl __print_int
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
mov x2, #1
mov x8, #64
svc #0
ldr x0, [sp, #8]
bl __print_float_scaled
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
mov x2, #1
mov x8, #64
svc #0
ldr x0, [sp, #16]
cmp x0, #0
b.ne L_BOOL_TRUE_7
mov x0, #1
adrp x1, __false_str
add x1, x1, :lo12:__false_str
mov x2, #5
mov x8, #64
svc #0
b L_BOOL_END_8
L_BOOL_TRUE_7:
mov x0, #1
adrp x1, __true_str
add x1, x1, :lo12:__true_str
mov x2, #4
mov x8, #64
svc #0
L_BOOL_END_8:
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
mov x2, #1
mov x8, #64
svc #0
ldr x0, [sp, #24]
bl __print_int
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
mov x2, #1
mov x8, #64
svc #0
ldr x0, [sp, #32]
ldr x1, [sp, #40]
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
adrp x0, __str_LIT_9
add x0, x0, :lo12:__str_LIT_9
mov x1, #39
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
str x0, [sp, #48]
mov x0, #2
str x0, [sp, #56]
adrp x0, __str_LIT_10
add x0, x0, :lo12:__str_LIT_10
mov x1, #15
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
adrp x0, __str_LIT_11
add x0, x0, :lo12:__str_LIT_11
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
mov x0, #7
str x0, [sp, #64]
ldr x0, =2500000000
str x0, [sp, #72]
mov x0, #1
str x0, [sp, #80]
mov x0, #88
str x0, [sp, #88]
adrp x0, __str_LIT_12
add x0, x0, :lo12:__str_LIT_12
mov x1, #10
str x0, [sp, #96]
str x1, [sp, #104]
ldr x0, [sp, #64]
bl __print_int
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
mov x2, #1
mov x8, #64
svc #0
ldr x0, [sp, #72]
bl __print_float_scaled
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
mov x2, #1
mov x8, #64
svc #0
ldr x0, [sp, #80]
cmp x0, #0
b.ne L_BOOL_TRUE_13
mov x0, #1
adrp x1, __false_str
add x1, x1, :lo12:__false_str
mov x2, #5
mov x8, #64
svc #0
b L_BOOL_END_14
L_BOOL_TRUE_13:
mov x0, #1
adrp x1, __true_str
add x1, x1, :lo12:__true_str
mov x2, #4
mov x8, #64
svc #0
L_BOOL_END_14:
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
mov x2, #1
mov x8, #64
svc #0
ldr x0, [sp, #88]
bl __print_int
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
mov x2, #1
mov x8, #64
svc #0
ldr x0, [sp, #96]
ldr x1, [sp, #104]
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
adrp x0, __str_LIT_15
add x0, x0, :lo12:__str_LIT_15
mov x1, #46
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
str x0, [sp, #112]
mov x0, #0
str x0, [sp, #120]
mov x0, #0
str x0, [sp, #128]
mov x0, #0
str x0, [sp, #136]
adrp x0, __empty_str
add x0, x0, :lo12:__empty_str
mov x1, #0
str x0, [sp, #144]
str x1, [sp, #152]
ldr x0, [sp, #112]
bl __print_int
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
mov x2, #1
mov x8, #64
svc #0
ldr x0, [sp, #120]
bl __print_float_scaled
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
mov x2, #1
mov x8, #64
svc #0
ldr x0, [sp, #128]
cmp x0, #0
b.ne L_BOOL_TRUE_16
mov x0, #1
adrp x1, __false_str
add x1, x1, :lo12:__false_str
mov x2, #5
mov x8, #64
svc #0
b L_BOOL_END_17
L_BOOL_TRUE_16:
mov x0, #1
adrp x1, __true_str
add x1, x1, :lo12:__true_str
mov x2, #4
mov x8, #64
svc #0
L_BOOL_END_17:
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
mov x2, #1
mov x8, #64
svc #0
ldr x0, [sp, #136]
bl __print_int
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
mov x2, #1
mov x8, #64
svc #0
ldr x0, [sp, #144]
ldr x1, [sp, #152]
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
adrp x0, __str_LIT_18
add x0, x0, :lo12:__str_LIT_18
mov x1, #33
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
str x0, [sp, #160]
mov x0, #20
str x0, [sp, #168]
adrp x0, __str_LIT_19
add x0, x0, :lo12:__str_LIT_19
mov x1, #4
str x0, [sp, #176]
str x1, [sp, #184]
adrp x0, __str_LIT_20
add x0, x0, :lo12:__str_LIT_20
mov x1, #5
str x0, [sp, #192]
str x1, [sp, #200]
ldr x0, [sp, #160]
bl __print_int
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
mov x2, #1
mov x8, #64
svc #0
ldr x0, [sp, #168]
bl __print_int
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
mov x2, #1
mov x8, #64
svc #0
ldr x0, [sp, #176]
ldr x1, [sp, #184]
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
ldr x0, [sp, #192]
ldr x1, [sp, #200]
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
adrp x0, __str_LIT_21
add x0, x0, :lo12:__str_LIT_21
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
ldr x0, =3141590000
str x0, [sp, #208]
mov x0, #1000
str x0, [sp, #216]
ldr x0, [sp, #208]
bl __print_float_scaled
mov x0, #1
adrp x1, __space_str
add x1, x1, :lo12:__space_str
mov x2, #1
mov x8, #64
svc #0
ldr x0, [sp, #216]
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
adrp x0, __str_LIT_22
add x0, x0, :lo12:__str_LIT_22
mov x1, #26
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
adrp x0, __str_LIT_23
add x0, x0, :lo12:__str_LIT_23
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
mov x0, #0
mov x0, #1
adrp x1, __nil_str
add x1, x1, :lo12:__nil_str
mov x2, #5
mov x8, #64
svc #0
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
adrp x0, __str_LIT_24
add x0, x0, :lo12:__str_LIT_24
mov x1, #23
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
mov x0, #1
adrp x1, __nil_str
add x1, x1, :lo12:__nil_str
mov x2, #5
mov x8, #64
svc #0
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
adrp x0, __str_LIT_25
add x0, x0, :lo12:__str_LIT_25
mov x1, #37
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
adrp x0, __str_LIT_26
add x0, x0, :lo12:__str_LIT_26
mov x1, #2
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
mov x0, #15
sub sp, sp, #16
str x0, [sp]
mov x0, #25
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
add x0, x19, x20
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
adrp x0, __str_LIT_27
add x0, x0, :lo12:__str_LIT_27
mov x1, #2
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
mov x0, #50
sub sp, sp, #16
str x0, [sp]
mov x0, #18
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
sub x0, x19, x20
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
adrp x0, __str_LIT_28
add x0, x0, :lo12:__str_LIT_28
mov x1, #2
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
mov x0, #7
sub sp, sp, #16
str x0, [sp]
mov x0, #8
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
mul x0, x19, x20
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
adrp x0, __str_LIT_29
add x0, x0, :lo12:__str_LIT_29
mov x1, #2
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
mov x0, #100
sub sp, sp, #16
str x0, [sp]
mov x0, #3
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
sdiv x0, x19, x20
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
adrp x0, __str_LIT_30
add x0, x0, :lo12:__str_LIT_30
mov x1, #2
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
mov x0, #17
sub sp, sp, #16
str x0, [sp]
mov x0, #5
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
sdiv x11, x19, x20
msub x0, x11, x20, x19
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
adrp x0, __str_LIT_31
add x0, x0, :lo12:__str_LIT_31
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
mov x0, #10
str x0, [sp, #224]
mov x0, #20
str x0, [sp, #232]
adrp x0, __str_LIT_32
add x0, x0, :lo12:__str_LIT_32
mov x1, #3
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
ldr x0, [sp, #224]
sub sp, sp, #16
str x0, [sp]
ldr x0, [sp, #232]
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
cmp x19, x20
cset x0, eq
cmp x0, #0
b.ne L_BOOL_TRUE_33
mov x0, #1
adrp x1, __false_str
add x1, x1, :lo12:__false_str
mov x2, #5
mov x8, #64
svc #0
b L_BOOL_END_34
L_BOOL_TRUE_33:
mov x0, #1
adrp x1, __true_str
add x1, x1, :lo12:__true_str
mov x2, #4
mov x8, #64
svc #0
L_BOOL_END_34:
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
adrp x0, __str_LIT_35
add x0, x0, :lo12:__str_LIT_35
mov x1, #3
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
ldr x0, [sp, #224]
sub sp, sp, #16
str x0, [sp]
ldr x0, [sp, #232]
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
cmp x19, x20
cset x0, ne
cmp x0, #0
b.ne L_BOOL_TRUE_36
mov x0, #1
adrp x1, __false_str
add x1, x1, :lo12:__false_str
mov x2, #5
mov x8, #64
svc #0
b L_BOOL_END_37
L_BOOL_TRUE_36:
mov x0, #1
adrp x1, __true_str
add x1, x1, :lo12:__true_str
mov x2, #4
mov x8, #64
svc #0
L_BOOL_END_37:
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
adrp x0, __str_LIT_38
add x0, x0, :lo12:__str_LIT_38
mov x1, #2
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
ldr x0, [sp, #224]
sub sp, sp, #16
str x0, [sp]
ldr x0, [sp, #232]
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
cmp x19, x20
cset x0, lt
cmp x0, #0
b.ne L_BOOL_TRUE_39
mov x0, #1
adrp x1, __false_str
add x1, x1, :lo12:__false_str
mov x2, #5
mov x8, #64
svc #0
b L_BOOL_END_40
L_BOOL_TRUE_39:
mov x0, #1
adrp x1, __true_str
add x1, x1, :lo12:__true_str
mov x2, #4
mov x8, #64
svc #0
L_BOOL_END_40:
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
adrp x0, __str_LIT_41
add x0, x0, :lo12:__str_LIT_41
mov x1, #2
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
ldr x0, [sp, #224]
sub sp, sp, #16
str x0, [sp]
ldr x0, [sp, #232]
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
cmp x19, x20
cset x0, gt
cmp x0, #0
b.ne L_BOOL_TRUE_42
mov x0, #1
adrp x1, __false_str
add x1, x1, :lo12:__false_str
mov x2, #5
mov x8, #64
svc #0
b L_BOOL_END_43
L_BOOL_TRUE_42:
mov x0, #1
adrp x1, __true_str
add x1, x1, :lo12:__true_str
mov x2, #4
mov x8, #64
svc #0
L_BOOL_END_43:
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
adrp x0, __str_LIT_44
add x0, x0, :lo12:__str_LIT_44
mov x1, #33
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
str x0, [sp, #240]
mov x0, #0
str x0, [sp, #248]
adrp x0, __str_LIT_45
add x0, x0, :lo12:__str_LIT_45
mov x1, #14
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
ldr x0, [sp, #240]
cmp x0, #0
b.eq L_AND_FALSE_46
ldr x0, [sp, #248]
cmp x0, #0
cset x0, ne
b L_AND_END_47
L_AND_FALSE_46:
mov x0, #0
L_AND_END_47:
cmp x0, #0
b.ne L_BOOL_TRUE_48
mov x0, #1
adrp x1, __false_str
add x1, x1, :lo12:__false_str
mov x2, #5
mov x8, #64
svc #0
b L_BOOL_END_49
L_BOOL_TRUE_48:
mov x0, #1
adrp x1, __true_str
add x1, x1, :lo12:__true_str
mov x2, #4
mov x8, #64
svc #0
L_BOOL_END_49:
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
adrp x0, __str_LIT_50
add x0, x0, :lo12:__str_LIT_50
mov x1, #14
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
ldr x0, [sp, #240]
cmp x0, #0
b.ne L_OR_TRUE_51
ldr x0, [sp, #248]
cmp x0, #0
cset x0, ne
b L_OR_END_52
L_OR_TRUE_51:
mov x0, #1
L_OR_END_52:
cmp x0, #0
b.ne L_BOOL_TRUE_53
mov x0, #1
adrp x1, __false_str
add x1, x1, :lo12:__false_str
mov x2, #5
mov x8, #64
svc #0
b L_BOOL_END_54
L_BOOL_TRUE_53:
mov x0, #1
adrp x1, __true_str
add x1, x1, :lo12:__true_str
mov x2, #4
mov x8, #64
svc #0
L_BOOL_END_54:
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
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
ldr x0, [sp, #240]
cmp x0, #0
cset x0, eq
cmp x0, #0
b.ne L_BOOL_TRUE_56
mov x0, #1
adrp x1, __false_str
add x1, x1, :lo12:__false_str
mov x2, #5
mov x8, #64
svc #0
b L_BOOL_END_57
L_BOOL_TRUE_56:
mov x0, #1
adrp x1, __true_str
add x1, x1, :lo12:__true_str
mov x2, #4
mov x8, #64
svc #0
L_BOOL_END_57:
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
adrp x0, __str_LIT_58
add x0, x0, :lo12:__str_LIT_58
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
mov x0, #0
str x0, [sp, #256]
mov x0, #0
cmp x0, #0
b.eq L_AND_FALSE_59
mov x0, #100
sub sp, sp, #16
str x0, [sp]
ldr x0, [sp, #256]
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
sdiv x0, x19, x20
sub sp, sp, #16
str x0, [sp]
mov x0, #1
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
cmp x19, x20
cset x0, eq
cmp x0, #0
cset x0, ne
b L_AND_END_60
L_AND_FALSE_59:
mov x0, #0
L_AND_END_60:
str x0, [sp, #264]
mov x0, #1
cmp x0, #0
b.ne L_OR_TRUE_61
mov x0, #100
sub sp, sp, #16
str x0, [sp]
ldr x0, [sp, #256]
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
sdiv x0, x19, x20
sub sp, sp, #16
str x0, [sp]
mov x0, #1
mov x20, x0
ldr x19, [sp]
add sp, sp, #16
cmp x19, x20
cset x0, eq
cmp x0, #0
cset x0, ne
b L_OR_END_62
L_OR_TRUE_61:
mov x0, #1
L_OR_END_62:
str x0, [sp, #272]
adrp x0, __str_LIT_63
add x0, x0, :lo12:__str_LIT_63
mov x1, #4
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
ldr x0, [sp, #264]
cmp x0, #0
b.ne L_BOOL_TRUE_64
mov x0, #1
adrp x1, __false_str
add x1, x1, :lo12:__false_str
mov x2, #5
mov x8, #64
svc #0
b L_BOOL_END_65
L_BOOL_TRUE_64:
mov x0, #1
adrp x1, __true_str
add x1, x1, :lo12:__true_str
mov x2, #4
mov x8, #64
svc #0
L_BOOL_END_65:
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
adrp x0, __str_LIT_66
add x0, x0, :lo12:__str_LIT_66
mov x1, #3
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
ldr x0, [sp, #272]
cmp x0, #0
b.ne L_BOOL_TRUE_67
mov x0, #1
adrp x1, __false_str
add x1, x1, :lo12:__false_str
mov x2, #5
mov x8, #64
svc #0
b L_BOOL_END_68
L_BOOL_TRUE_67:
mov x0, #1
adrp x1, __true_str
add x1, x1, :lo12:__true_str
mov x2, #4
mov x8, #64
svc #0
L_BOOL_END_68:
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
adrp x0, __str_LIT_69
add x0, x0, :lo12:__str_LIT_69
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
mov x0, #50
str x0, [sp, #280]
ldr x0, [sp, #280]
mov x9, x0
mov x0, #10
mov x10, x0
add x0, x9, x10
str x0, [sp, #280]
ldr x0, [sp, #280]
mov x9, x0
mov x0, #5
mov x10, x0
sub x0, x9, x10
str x0, [sp, #280]
ldr x0, [sp, #280]
mov x9, x0
mov x0, #2
mov x10, x0
mul x0, x9, x10
str x0, [sp, #280]
ldr x0, [sp, #280]
mov x9, x0
mov x0, #5
mov x10, x0
sdiv x0, x9, x10
str x0, [sp, #280]
adrp x0, __str_LIT_70
add x0, x0, :lo12:__str_LIT_70
mov x1, #16
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
ldr x0, [sp, #280]
bl __print_int
mov x0, #1
adrp x1, __newline_str
add x1, x1, :lo12:__newline_str
mov x2, #1
mov x8, #64
svc #0
adrp x0, __str_LIT_71
add x0, x0, :lo12:__str_LIT_71
mov x1, #53
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
b.ge L_PI_POS_74
neg x9, x9
mov x13, #1
L_PI_POS_74:
cmp x9, #0
b.ne L_PI_LOOP_72
sub x11, x11, #1
mov w12, #48
strb w12, [x11]
b L_PI_AFTER_DIGITS_73
L_PI_LOOP_72:
mov x15, #10
udiv x14, x9, x15
msub x16, x14, x15, x9
add x16, x16, #48
sub x11, x11, #1
strb w16, [x11]
mov x9, x14
cmp x9, #0
b.ne L_PI_LOOP_72
L_PI_AFTER_DIGITS_73:
cmp x13, #0
b.eq L_PI_NOSIGN_75
sub x11, x11, #1
mov w12, #45
strb w12, [x11]
L_PI_NOSIGN_75:
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
b.ge L_PF_POS_76
mov x0, #1
adrp x1, __minus_str
add x1, x1, :lo12:__minus_str
mov x2, #1
mov x8, #64
svc #0
neg x9, x9
b L_PF_AFTER_SIGN_77
L_PF_POS_76:
L_PF_AFTER_SIGN_77:
udiv x10, x9, x15
msub x11, x10, x15, x9
mov x0, x10
mov x20, x11
bl __print_int
ldr x15, =1000000000
mov x11, x20
cmp x11, #0
b.eq L_PF_NO_FRAC_78
mov x0, #1
adrp x1, __dot_str
add x1, x1, :lo12:__dot_str
mov x2, #1
mov x8, #64
svc #0
adrp x12, __frac_buffer
add x12, x12, :lo12:__frac_buffer
mov x13, #0
L_PF_DIGIT_LOOP_79:
cmp x13, #9
b.ge L_PF_DIGIT_DONE_80
mov x14, #10
mul x11, x11, x14
udiv x16, x11, x15
msub x11, x16, x15, x11
add x16, x16, #48
strb w16, [x12, x13]
add x13, x13, #1
b L_PF_DIGIT_LOOP_79
L_PF_DIGIT_DONE_80:
mov x17, #8
L_PF_TRIM_LOOP_81:
ldrb w16, [x12, x17]
cmp w16, #48
b.ne L_PF_TRIM_DONE_82
subs x17, x17, #1
b.pl L_PF_TRIM_LOOP_81
mov x17, #0
L_PF_TRIM_DONE_82:
add x2, x17, #1
mov x0, #1
mov x1, x12
mov x8, #64
svc #0
L_PF_NO_FRAC_78:
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
__str_LIT_0: .ascii "=== INICIO DE CALIFICACION: FUNCIONALIDADES BASICAS ==="
__str_LIT_0_len = . - __str_LIT_0
__str_LIT_1: .ascii "\n--- 1.1 DECLARACION LARGA ---"
__str_LIT_1_len = . - __str_LIT_1
__str_LIT_2: .ascii "Golampi"
__str_LIT_2_len = . - __str_LIT_2
__str_LIT_5: .ascii "\n--- 1.2 ASIGNACION DE VARIABLES ---"
__str_LIT_5_len = . - __str_LIT_5
__str_LIT_6: .ascii "Actualizado"
__str_LIT_6_len = . - __str_LIT_6
__str_LIT_9: .ascii "\n--- 1.3 FORMATO DE IDENTIFICADORES ---"
__str_LIT_9_len = . - __str_LIT_9
__str_LIT_10: .ascii "Case sensitive:"
__str_LIT_10_len = . - __str_LIT_10
__str_LIT_11: .ascii "\n--- 1.4 DECLARACION CORTA ---"
__str_LIT_11_len = . - __str_LIT_11
__str_LIT_12: .ascii "Inferencia"
__str_LIT_12_len = . - __str_LIT_12
__str_LIT_15: .ascii "\n--- 1.5 DECLARACION LARGA SIN INICIALIZAR ---"
__str_LIT_15_len = . - __str_LIT_15
__str_LIT_18: .ascii "\n--- 1.6 DECLARACION MULTIPLE ---"
__str_LIT_18_len = . - __str_LIT_18
__str_LIT_19: .ascii "Hola"
__str_LIT_19_len = . - __str_LIT_19
__str_LIT_20: .ascii "Mundo"
__str_LIT_20_len = . - __str_LIT_20
__str_LIT_21: .ascii "\n--- 1.7 CONSTANTES ---"
__str_LIT_21_len = . - __str_LIT_21
__str_LIT_22: .ascii "\n--- 1.8 MANEJO DE NIL ---"
__str_LIT_22_len = . - __str_LIT_22
__str_LIT_23: .ascii "Impresion de nil:"
__str_LIT_23_len = . - __str_LIT_23
__str_LIT_24: .ascii "Comparacion nil == nil:"
__str_LIT_24_len = . - __str_LIT_24
__str_LIT_25: .ascii "\n--- 1.11 OPERACIONES ARITMETICAS ---"
__str_LIT_25_len = . - __str_LIT_25
__str_LIT_26: .ascii "+:"
__str_LIT_26_len = . - __str_LIT_26
__str_LIT_27: .ascii "-:"
__str_LIT_27_len = . - __str_LIT_27
__str_LIT_28: .ascii "*:"
__str_LIT_28_len = . - __str_LIT_28
__str_LIT_29: .ascii "/:"
__str_LIT_29_len = . - __str_LIT_29
__str_LIT_30: .ascii "%:"
__str_LIT_30_len = . - __str_LIT_30
__str_LIT_31: .ascii "\n--- 1.12 OPERACIONES RELACIONALES ---"
__str_LIT_31_len = . - __str_LIT_31
__str_LIT_32: .ascii "==:"
__str_LIT_32_len = . - __str_LIT_32
__str_LIT_35: .ascii "!=:"
__str_LIT_35_len = . - __str_LIT_35
__str_LIT_38: .ascii "<:"
__str_LIT_38_len = . - __str_LIT_38
__str_LIT_41: .ascii ">:"
__str_LIT_41_len = . - __str_LIT_41
__str_LIT_44: .ascii "\n--- 1.13 OPERACIONES LOGICAS ---"
__str_LIT_44_len = . - __str_LIT_44
__str_LIT_45: .ascii "true && false:"
__str_LIT_45_len = . - __str_LIT_45
__str_LIT_50: .ascii "true || false:"
__str_LIT_50_len = . - __str_LIT_50
__str_LIT_55: .ascii "!true:"
__str_LIT_55_len = . - __str_LIT_55
__str_LIT_58: .ascii "\n--- 1.14 CORTO CIRCUITO ---"
__str_LIT_58_len = . - __str_LIT_58
__str_LIT_63: .ascii "AND:"
__str_LIT_63_len = . - __str_LIT_63
__str_LIT_66: .ascii "OR:"
__str_LIT_66_len = . - __str_LIT_66
__str_LIT_69: .ascii "\n--- 1.15 OPERADORES DE ASIGNACION ---"
__str_LIT_69_len = . - __str_LIT_69
__str_LIT_70: .ascii "Resultado final:"
__str_LIT_70_len = . - __str_LIT_70
__str_LIT_71: .ascii "\n=== FIN DE CALIFICACION: FUNCIONALIDADES BASICAS ==="
__str_LIT_71_len = . - __str_LIT_71
