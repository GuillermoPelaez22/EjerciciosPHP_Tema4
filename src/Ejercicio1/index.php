<?php 
session_start();

// Inicialización de la lista de colores en la sesión
if (!isset($_SESSION['colores'])) {
    $_SESSION['colores'] = [];
}

// Procesa la generación de un nuevo color aleatorio Hexadecimal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_color'])) {
    $nuevoColor = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    $_SESSION['colores'][] = $nuevoColor;
    $_SESSION['fondo_actual'] = $nuevoColor;
}

$colorFondo = $_SESSION['fondo_actual'] ??  '#ffffff';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 1 - Generador de Colores</title>
</head>
<body style="background-color: <?= htmlspecialchars($colorFondo) ?>;">
    <h2>Generador de Colores de Fondo</h2>

    <form action="index.php" method="post">
        <button type="submit" name="add_color">Añadir color</button>
    </form>
    <br>
    <a href="paleta.php"><button type="button">Mostrar paleta creada</button></a>
</body>
</html>