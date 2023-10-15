<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="cache-control" content="no-store" />
    <meta http-equiv="pragma" content="no-store" />
    <title>Sliding Puzzle</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
        }

        h1 {
            color: #333;
            margin:0;
        }

        .board {
            display: grid;
            grid-template-columns: repeat(3, 100px);
            grid-gap: 5px;
            margin: 20px auto;
        }

        .tile {
            width: 100px;
            height: 100px;
            border: 1px solid #000;
            text-align: center;
            font-size: 24px;
            line-height: 100px;
        }

        form {
            margin: 0px;
        }

        select, input[type="number"], input[type="submit"] {
            padding: 10px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <h1>Puzzle</h1>
<?php
    session_start(); // Iniciar la sesión
// Inicializar el contador si no está configurado en la sesión
if (!isset($_SESSION['contador'])) {
    $_SESSION['contador'] = 0;
}



// Inicializar el tablero si no está configurado en la sesión
if (!isset($_SESSION['board'])) {
    $board = [[7, 4, 6], [8, 0, 2], [5, 1, 3]];  // Estado inicial del tablero
    $_SESSION['board'] = $board;
} else {
    $board = $_SESSION['board'];
}

    // Función para imprimir el tablero
    function printBoard($board) {
        echo '<div class="board">';
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                echo "<div class='tile'>" . $board[$i][$j] . "</div>";
            }
        }
        echo '</div>';
    }

    // Función para verificar si el juego se ha resuelto
    function isSolved($board) {
        $solvedBoard = [[1, 2, 3], [4, 5, 6], [7, 8, 0]];  // Estado resuelto del tablero
        return $board == $solvedBoard;
    }
// Obtener el valor actual del contador
$contador = $_SESSION['contador'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numberToMove = $_POST["number"];
    $direction = $_POST["direction"];

    // Mover el número en la dirección seleccionada
    $blankX = -1;
    $blankY = -1;

    // Encuentra la posición del espacio en blanco
    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            if ($board[$i][$j] == 0) {
                $blankX = $i;
                $blankY = $j;
                break;
            }
        }
    }

    // Verifica si el número seleccionado es válido
    if (is_numeric($numberToMove) && $numberToMove >= 1 && $numberToMove <= 8) {
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                if ($board[$i][$j] == $numberToMove) {
                    $targetX = $i;
                    $targetY = $j;
                    break;
                }
            }
        }

        // Comprueba si la casilla seleccionada se encuentra en la dirección hacia donde se quiere mover
        $validMove = false;

        if ($direction == "arriba" && $targetX == $blankX + 1 && $targetY == $blankY) {
            $validMove = true;
        } elseif ($direction == "abajo" && $targetX == $blankX - 1 && $targetY == $blankY) {
            $validMove = true;
        } elseif ($direction == "izquierda" && $targetX == $blankX && $targetY == $blankY + 1) {
            $validMove = true;
        } elseif ($direction == "derecha" && $targetX == $blankX && $targetY == $blankY - 1) {
            $validMove = true;
        }

        if ($validMove) {
            // Intercambia los valores
            $board[$blankX][$blankY] = $numberToMove;
            $board[$targetX][$targetY] = 0;
            $_SESSION['contador']++; // Incrementa el contador solo cuando el movimiento es válido
        } else {
            echo "Movimiento no válido";
        }
        $_SESSION['board'] = $board; // Actualizar el tablero en la sesión
    }
}

    printBoard($board);

    if (isSolved($board)) {
        echo "<h2>Felicidades, has resuelto el rompecabezas.</h2>";
    }
    echo "<p>Número de movimientos: $contador</p>";
    ?>

    <h2>Mover el número:</h2>
    <form method="post">
        <input type="number" name="number" min="1" max="8" placeholder="Número a mover (1-8)">
        en dirección:
        <select name="direction">
            <option value="arriba">Arriba</option>
            <option value="abajo">Abajo</option>
            <option value="izquierda">Izquierda</option>
            <option value="derecha">Derecha</option>
        </select>
        <input type="submit" value="Mover">
    </form>
</body>
</html>