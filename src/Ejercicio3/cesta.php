<?php
session_start();

$cesta = $_SESSION['cesta'] ?? [];
$catalogo = $_SESSION['catalogo'] ?? [];

// Eliminar una unidad o borrar el elemento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $prod = $_POST['eliminar'];
    if (isset($cesta[$prod])) {
        $cesta[$prod]--;
        if ($cesta[$prod] <= 0) {
            unset($cesta[$prod]);
        }
    }
    $_SESSION['cesta'] = $cesta;
    setcookie('cesta_tienda', json_encode($cesta), time() + (7 * 24 * 3600));
}

// Vaciar la cesta completa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vaciar'])) {
    $_SESSION['cesta'] = [];
    setcookie('cesta_tienda', '', time() - 3600);
    $cesta = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cesta de la compra</title>
    <style>
        table { border-collapse: collapse; width: 450px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
    </style>
</head>
<body>

    <h2>PRODUCTOS EN TU CESTA</h2>

    <?php if (empty($cesta)): ?>
        <p>No hay productos en la cesta.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Unidades</th>
                    <th>Precio Unid.</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalImporte = 0;
                foreach ($cesta as $nombre => $cantidad): 
                    $precio = $catalogo[$nombre]['precio'] ?? 0;
                    $subtotal = $precio * $cantidad;
                    $totalImporte += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($nombre) ?></td>
                    <td><?= $cantidad ?></td>
                    <td><?= $precio ?> €</td>
                    <td>
                        <form action="cesta.php" method="post">
                            <button type="submit" name="eliminar" value="<?= htmlspecialchars($nombre) ?>">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="2"><strong>Total</strong></td>
                    <td colspan="2"><strong><?= $totalImporte ?> €</strong></td>
                </tr>
            </tbody>
        </table>
        <br>
        <form action="cesta.php" method="post">
            <button type="submit" name="vaciar">VACIAR CESTA</button>
        </form>
    <?php endif; ?>

    <br>
    <a href="tienda.php"><button type="button">Volver a la tienda</button></a>

</body>
</html>