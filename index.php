<?php
session_start();

$palavras = [
    "COMPUTADOR",
    "PROGRAMACAO",
    "INTERNET",
    "FACULDADE",
    "PHP"
];

if (!isset($_SESSION['palavra'])) {
    $_SESSION['palavra'] = $palavras[array_rand($palavras)];
    $_SESSION['acertos'] = [];
    $_SESSION['erros'] = 0;
}

if (isset($_POST['reiniciar'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

if (isset($_POST['letra'])) {

    $letra = strtoupper(trim($_POST['letra']));

    if ($letra != "") {

        if (strpos($_SESSION['palavra'], $letra) !== false) {

            if (!in_array($letra, $_SESSION['acertos'])) {
                $_SESSION['acertos'][] = $letra;
            }

        } else {

            if ($_SESSION['erros'] < 6) {
                $_SESSION['erros']++;
            }
        }
    }
}

$exibicao = "";

foreach (str_split($_SESSION['palavra']) as $char) {

    if (in_array($char, $_SESSION['acertos'])) {
        $exibicao .= $char . " ";
    } else {
        $exibicao .= "_ ";
    }
}

$venceu = !str_contains($exibicao, "_");
$perdeu = $_SESSION['erros'] >= 6;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Forca PHP</title>

    <style>

        body{
            font-family: Arial, sans-serif;
            text-align:center;
            margin-top:100px;
        }

        .palavra{
            font-size:35px;
            letter-spacing:10px;
            margin-bottom:20px;
        }

        input{
            padding:8px;
        }

        button{
            padding:8px 15px;
            cursor:pointer;
        }

    </style>

</head>

<body>

    <h1>Jogo da Forca - PHP</h1>

    <div class="palavra">
        <?= $exibicao ?>
    </div>

    <p>
        Erros: <?= $_SESSION['erros'] ?>/6
    </p>

    <?php if (!$venceu && !$perdeu): ?>

        <form method="POST">

            <input
                type="text"
                name="letra"
                maxlength="1"
                required>

            <button type="submit">
                Tentar
            </button>

        </form>

    <?php endif; ?>

    <?php if ($venceu): ?>

        <h2>Você venceu!</h2>

    <?php endif; ?>

    <?php if ($perdeu): ?>

        <h2>
            Você perdeu!
            A palavra era:
            <?= $_SESSION['palavra'] ?>
        </h2>

    <?php endif; ?>

    <br>

    <form method="POST">

        <button
            type="submit"
            name="reiniciar">

            Novo Jogo

        </button>

    </form>

</body>

</html>
