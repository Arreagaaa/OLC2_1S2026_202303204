#!/bin/sh

cd "$(dirname "$0")"

aarch64-linux-gnu-as output.txt -o output.o
aarch64-linux-gnu-ld output.o -o output

qemu-aarch64 ./output
