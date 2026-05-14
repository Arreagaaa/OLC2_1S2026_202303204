<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>C3D T2</title>
    <style>
        body {
            background-color: #1e1e2f;
            color: #f0f0f0;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            color: #ffcc00;
            margin-bottom: 16px;
        }

        form {
            width: 100%;
            max-width: 800px;
            margin-bottom: 20px;
        }

        .editor-container {
            display: flex;
            border-radius: 10px;
            overflow: hidden;
            background-color: #2e2e44;
            border: 1px solid #444;
            height: 260px;
            width: 100%;
        }

        .line-numbers {
            background-color: #2a2a3d;
            color: #888;
            font-family: monospace;
            font-size: 14px;
            text-align: right;
            padding: 10px 5px;
            user-select: none;
            white-space: pre;
            line-height: 1.4em;
        }

        textarea {
            flex: 1;
            border: none;
            outline: none;
            background: none;
            color: #f0f0f0;
            font-family: monospace;
            font-size: 14px;
            line-height: 1.4em;
            padding: 10px;
            resize: none;
        }

        input[type="submit"] {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #ffcc00;
            color: #1e1e2f;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #e6b800;
        }

        .console {
            width: 100%;
            max-width: 800px;
            background-color: #2e2e44;
            padding: 15px;
            border-radius: 10px;
            font-family: monospace;
            white-space: pre;
            min-height: 50px;
            overflow-x: auto;
        }
    </style>
</head>

<body>

    <?php
    require __DIR__ . '/vendor/autoload.php';
    require_once 'bootstrap.php';

    use Antlr\Antlr4\Runtime\InputStream;
    use Antlr\Antlr4\Runtime\CommonTokenStream;
    use Antlr\Antlr4\Runtime\Error\BailErrorStrategy;
    use Antlr\Antlr4\Runtime\Error\Exceptions\ParseCancellationException;
    use Antlr\Antlr4\Runtime\Error\Exceptions\InputMismatchException;

    $input  = "";
    $output = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $input = $_POST["code"] ?? "";

        if (!empty($input)) {
            try {
                $inputStream = InputStream::fromString($input);
                $lexer       = new GrammarLexer($inputStream);
                $tokens      = new CommonTokenStream($lexer);
                $parser      = new GrammarParser($tokens);

                $parser->setErrorHandler(new BailErrorStrategy());

                $tree = $parser->p(); // regla inicial

                $compiler = new Compiler();
                $result   = $compiler->visit($tree);

                $output = $result->toString();
            } catch (ParseCancellationException $e) {
                $cause = $e->getPrevious();

                if ($cause instanceof InputMismatchException) {
                    $offending = $cause->getOffendingToken();
                    $found     = $offending ? $offending->getText() : 'EOF';

                    $parserObj     = $cause->getRecognizer();
                    $vocab         = $parserObj->getVocabulary();
                    $expectedNames = [];

                    foreach ($cause->getExpectedTokens()->toArray() as $t) {
                        $expectedNames[] = $vocab->getDisplayName($t);
                    }

                    $output = sprintf(
                        "Error sintáctico en línea %d, columna %d: se esperaba %s y se encontró '%s'",
                        $offending->getLine(),
                        $offending->getCharPositionInLine(),
                        implode(" o ", $expectedNames),
                        $found
                    );
                } else {
                    $output = "Error de parseo: " . $e->getMessage();
                }
            } catch (Exception $e) {
                $output = "Error: " . $e->getMessage();
            }
        } else {
            $output = "Por favor ingresá código para compilar.";
        }
    }
    ?>

    <h2>Código fuente</h2>

    <form method="post">
        <div class="editor-container">
            <div class="line-numbers" id="lineNumbers">1</div>
            <textarea
                id="editor"
                name="code"
                placeholder="Escribí tu código aquí..."><?php echo htmlspecialchars($input); ?></textarea>
        </div>
        <input type="submit" value="Compilar a C3D">
    </form>

    <h2>C3D generado:</h2>
    <div class="console"><?php echo htmlspecialchars($output); ?></div>

    <script>
        const editor = document.getElementById('editor');
        const lineNumbers = document.getElementById('lineNumbers');

        function updateLineNumbers() {
            const lines = editor.value.split('\n').length;
            let numbers = '';
            for (let i = 1; i <= lines; i++) numbers += i + '\n';
            lineNumbers.textContent = numbers;
        }

        updateLineNumbers();
        editor.addEventListener('input', updateLineNumbers);
        editor.addEventListener('scroll', () => {
            lineNumbers.scrollTop = editor.scrollTop;
        });
    </script>

</body>

</html>