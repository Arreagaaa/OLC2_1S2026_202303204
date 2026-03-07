#!/usr/bin/env bash
# genera los archivos PHP desde la gramatica usando ANTLR4
# requiere: java en PATH, antlr4 jar en ~/antlr4.jar (o definir ANTLR4_JAR)

ANTLR4_JAR="${ANTLR4_JAR:-$HOME/antlr4.jar}"
GRAMMAR="grammar/Golampi.g4"
OUT_DIR="backend/generated"

# verificar que java exista
if ! command -v java &>/dev/null; then
    echo "Error: java no encontrado en PATH"
    exit 1
fi

# verificar que el jar exista
if [ ! -f "$ANTLR4_JAR" ]; then
    echo "Descargando ANTLR4 jar..."
    curl -Lo "$ANTLR4_JAR" \
        "https://www.antlr.org/download/antlr-4.13.2-complete.jar"
fi

mkdir -p "$OUT_DIR"

# copiar la gramatica al directorio de salida para que ANTLR no anide subcarpetas
cp "$GRAMMAR" "$OUT_DIR/Golampi.g4"

java -jar "$ANTLR4_JAR" \
    -Dlanguage=PHP \
    -visitor \
    -no-listener \
    -o "$OUT_DIR" \
    "$OUT_DIR/Golampi.g4"

# limpiar la copia temporal de la gramatica
rm -f "$OUT_DIR/Golampi.g4"

echo "Archivos generados en $OUT_DIR"
