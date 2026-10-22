<?php 
session_start();

// Cambiar el color del fondo al hacer clic en una celda
if (isset($_GET['set_fondo'])) {
    $_SESSION['fondo_actual'] = $_GET['set_fondo'];
}

// Destruir la sesión y redirigir
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['destruir'])) {
    session_destroy();
    header("Location: index.php");
    exit();    
}

$colores = $_SESSION['colores'] ?? [];
$colorFondo = $_SESSION['fondo_actual'] ?? '#ffffff';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paleta de Colores</title>
    <style>
        table { border-collapse: collapse; margin-bottom: 20px; }
        td { width: 60px; height: 60px; border: 1px solid #000; cursor: pointer; }
    </style>
</head>
<body style="background-color: <?= htmlspecialchars($colorFondo) ?>">
    
    <h2>Paleta de Colores Creada</h2>

    <?php if (empty($colores)): ?>
        <p>No se han generado colores en esta sesión.</p>
    <?php else: ?>
        <table>
            <tr>
                <?php foreach ($colores as $index => $color): ?>
                    <?php if ($index > 0 && $index % 5 === 0): ?>
                            </tr><tr>
                    <?php endif; ?>
                    <td style="background-color: <?= htmlspecialchars($color) ?>;"
                        onclick="window.location.href='paletaphp?ser_fondo=<?= urlencode($color) ?>'"></td>
            <?php endforeach; ?>
            </tr>
        </table>
    <?php endif; ?>

    <a href="index.php"><button type="button">Volver a la página principal</button></a>

    <form action="paleta.php" method="post" style="display:inline">
        <button type="submit" name="destruir">Generar nueva paleta</button>
    </form>
    
</body>
</html>
