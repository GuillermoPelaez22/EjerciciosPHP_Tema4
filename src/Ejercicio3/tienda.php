<?php 
session_start();

// Carga inicial del catálogo base
if (!isset($_SESSION['catalogo'])) {
    $_SESSION['catalogo'] = [
    'raton'    => ['precio' => 6,  'detalle' => 'Ratón óptico ergonómico con cable USB.'],
        'teclado'  => ['precio' => 11, 'detalle' => 'Teclado mecánico con retroiluminación RGB.'],
        'monitor'  => ['precio' => 80, 'detalle' => 'Monitor LED Full HD de 24 pulgadas.'],
        'joystick' => ['precio' => 33, 'detalle' => 'Mando analógico para juegos de PC.']
    ];
}

// Carga de la cesta desde la cookie si existe
if (!isset($_SESSION['cesta']) && isset($_COOKIE['cesta_tienda'])) {
    $_SESSION['cesta'] = json_decode($_COOKIE['cesta_tienda'], true);    
} elseif (!isset($_SESSION['cesta'])) {
    $_SESSION['cesta'] = [];
}

// Acción de añadir al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comprar'])) {
    $producto = $_POST['comprar'];
    $_SESSION['cesta'][$producto] = ($_SESSION['cesta'][$producto] ?? 0) + 1;
    setcookie('cesta_tienda', json_encode($_SESSION['cesta']), time() + 60);
}

$totalUnidades = array_sum($_SESSION['cesta']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La tiendecita</title>
    <style>
        table { border-collapse: collapse; width: 420px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        img { width: 50px; height: 50px; object-fit: cover; }
    </style>
</head>
<body>

    <h2>La tiendecita</h2>
    <p>CESTA: <?= $totalUnidades ?> Prod | <a href="cesta.php">Ver Cesta</a></p>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Imagen</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['catalogo'] as $nombre => $datos): ?>
            <tr>
                <td><?= htmlspecialchars($nombre) ?></td>
                <td><?= $datos['precio'] ?> €</td>
                <td>
                    <a href="detalle.php?producto=<?= urlencode($nombre) ?>">
                        <img src="<?= htmlspecialchars($nombre) ?>.png" alt="<?= htmlspecialchars($nombre) ?>" onerror="this.src='https://via.placeholder.com/50';">
                    </a>
                </td>
                <td>
                    <form action="tienda.php" method="post">
                        <button type="submit" name="comprar" value="<?= htmlspecialchars($nombre) ?>">Comprar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>