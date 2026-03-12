// ══════════════════════════════════════════════════
// TEST RECURSIVIDAD Y ALGORITMOS — Golampi
// ══════════════════════════════════════════════════

// ── Fibonacci recursivo ───────────────────────────
func fibonacci(n int32) int32 {
    if n <= 0 {
        return 0
    }
    if n == 1 {
        return 1
    }
    return fibonacci(n - 1) + fibonacci(n - 2)
}

// ── Suma recursiva de 1 a n ───────────────────────
func sumaRecursiva(n int32) int32 {
    if n <= 0 {
        return 0
    }
    return n + sumaRecursiva(n - 1)
}

// ── Potencia recursiva ────────────────────────────
func potencia(base int32, exp int32) int32 {
    if exp == 0 {
        return 1
    }
    return base * potencia(base, exp - 1)
}

// ── Cuenta regresiva recursiva ────────────────────
func cuentaRegresiva(n int32) {
    if n < 0 {
        return
    }
    fmt.Println(n)
    cuentaRegresiva(n - 1)
}

// ── Torres de Hanoi ───────────────────────────────
func hanoi(n int32, origen string, destino string, auxiliar string) {
    if n == 1 {
        fmt.Println("Mover disco 1 de " + origen + " a " + destino)
        return
    }
    hanoi(n - 1, origen, auxiliar, destino)
    fmt.Println("Mover disco " + typeOf(n) + " de " + origen + " a " + destino)
    hanoi(n - 1, auxiliar, destino, origen)
}

// ── Bubble sort recursivo ─────────────────────────
func bubblePass(a *[5]int32, n int32) {
    if n <= 1 {
        return
    }
    for i := 0; i < n - 1; i++ {
        if a[i] > a[i+1] {
            var temp int32 = a[i]
            a[i] = a[i+1]
            a[i+1] = temp
        }
    }
    bubblePass(a, n - 1)
}

// ── MCD (Máximo Común Divisor) — Algoritmo de Euclides ──
func mcd(a int32, b int32) int32 {
    if b == 0 {
        return a
    }
    return mcd(b, a % b)
}

// ── Es primo ──────────────────────────────────────
func esPrimo(n int32, divisor int32) bool {
    if n < 2 {
        return false
    }
    if divisor * divisor > n {
        return true
    }
    if n % divisor == 0 {
        return false
    }
    return esPrimo(n, divisor + 1)
}

func main() {
    // 1. Fibonacci
    fmt.Println("=== Fibonacci ===")
    for i := 0; i <= 8; i++ {
        fmt.Println(fibonacci(i))
    }

    // 2. Suma recursiva
    fmt.Println("=== Suma recursiva 1..10 ===")
    fmt.Println(sumaRecursiva(10))

    // 3. Potencia recursiva
    fmt.Println("=== Potencias ===")
    fmt.Println(potencia(2, 0))
    fmt.Println(potencia(2, 5))
    fmt.Println(potencia(3, 4))

    // 4. Cuenta regresiva
    fmt.Println("=== Cuenta regresiva ===")
    cuentaRegresiva(5)

    // 5. Torres de Hanoi con 3 discos
    fmt.Println("=== Torres de Hanoi (3 discos) ===")
    hanoi(3, "A", "C", "B")

    // 6. Bubble sort recursivo
    fmt.Println("=== Bubble Sort recursivo ===")
    var arr [5]int32 = [5]int32{64, 34, 25, 12, 22}
    bubblePass(&arr, 5)
    fmt.Println(arr[0])
    fmt.Println(arr[1])
    fmt.Println(arr[2])
    fmt.Println(arr[3])
    fmt.Println(arr[4])

    // 7. MCD
    fmt.Println("=== MCD ===")
    fmt.Println(mcd(48, 18))
    fmt.Println(mcd(100, 75))

    // 8. Números primos del 1 al 20
    fmt.Println("=== Primos del 1 al 20 ===")
    for n := 2; n <= 20; n++ {
        if esPrimo(n, 2) {
            fmt.Println(n)
        }
    }
}
