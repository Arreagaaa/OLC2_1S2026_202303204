func f(x int) int {
    fmt.Println(x)
    return x
}

func g(a int, b int) int {
    return a + b
}

func main() {
    r := g(f(1), f(2))
    fmt.Println(r)
}
