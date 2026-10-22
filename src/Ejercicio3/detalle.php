<?php
session_start();

$nombre = $_GET['producto'] ?? '';
$catalogo = $_SESSION['catalogo'] ?? [];

if (!isset($catalogo[$nombre])) {
    header('Location: tienda.php');
    exit();
}

$producto = $catalogo[$nombre];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle - <?= htmlspecialchars($nombre) ?></title>
</head>
<body>

    <h2>Detalle del Producto: <?= htmlspecialchars(ucfirst($nombre)) ?></h2>

    <img src="<?= htmlspecialchars($nombre) ?>.png" alt="<?= htmlspecialchars($nombre) ?>" style="width:150px; height:150px;" onerror="this.src='https://via.placeholder.com/150';">

    <p><strong>Precio:</strong> <?= $producto['precio'] ?> €</p>
    <p><strong>Descripción:</strong> <?= htmlspecialchars($producto['detalle']) ?></p>

    <a href="tienda.php"><button type="button">Volver a la tienda</button></a>

</body>
</html>