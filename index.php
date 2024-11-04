<?php
session_start(); 


$palabras = ['elefante', 'jirafa', 'hipopotamo', 'rinoceronte', 'cocodrilo', 'camello', 'chimpance'];


if (!isset($_SESSION['palabra'])) {
    $_SESSION['palabra'] = $palabras[array_rand($palabras)];
    $_SESSION['vidas'] = 6; 
    $_SESSION['letras_acertadas'] = str_repeat('?', strlen($_SESSION['palabra']));
    $_SESSION['letras_usadas'] = [];
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['letra'])) {
    $letra = strtolower($_POST['letra']);

 
    if (in_array($letra, $_SESSION['letras_usadas'])) {
        echo "Ya has usado la letra '$letra'. Intenta con otra.<br>";
    } else {
        
        $_SESSION['letras_usadas'][] = $letra;

        
        if (strpos($_SESSION['palabra'], $letra) !== false) {
            for ($i = 0; $i < strlen($_SESSION['palabra']); $i++) {
                if ($_SESSION['palabra'][$i] == $letra) {
                    $_SESSION['letras_acertadas'][$i] = $letra;
                }
            }
        } else {
            $_SESSION['vidas']--;
        }
    }
}


if ($_SESSION['letras_acertadas'] == $_SESSION['palabra']) {
    echo "¡Enhorabuena! Has ganado :) La palabra era: " . $_SESSION['palabra'] . "<br>";
    session_destroy();
    echo '<a href="">Jugar de nuevo</a>';
    exit();
} elseif ($_SESSION['vidas'] <= 0) {
    echo "Lo siento, has perdido :( La palabra era: " . $_SESSION['palabra'] . "<br>";
    session_destroy();
    echo '<a href="">Jugar de nuevo</a>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ahorcado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h1 {
            color: #333;
        }
        .vidas, .letras-usadas {
            font-size: 18px;
            margin: 10px 0;
        }
        form input[type="text"] {
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 50px;
            text-align: center;
        }
        form button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Juego del Ahorcado</h1>
        <p class="vidas">Vidas restantes: <?php echo isset($_SESSION['vidas']) ? $_SESSION['vidas'] : 0; ?></p>
        <p>Palabra secreta: <?php echo isset($_SESSION['letras_acertadas']) ? $_SESSION['letras_acertadas'] : ''; ?></p>
        <form method="post">
            <label for="letra">Introduce una letra:</label>
            <input type="text" name="letra" id="letra" maxlength="1" required>
            <button type="submit">Adivinar</button>
        </form>
        <p class="letras-usadas">Letras usadas: <?php echo isset($_SESSION['letras_usadas']) ? implode(', ', $_SESSION['letras_usadas']) : ''; ?></p>
    </div>
</body>
</html>