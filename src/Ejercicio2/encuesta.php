<?php 
session_start();

// Constante de tiempo para la cookie: 3 meses
define('TIEMPO_COOKIE', 90 * 24 * 3600);

// Cargar o inicializar variavbles de votos desde la cookie o la sesion
if (!isset($_SESSION['votos_si']) || !isset($_SESSION['votos_no'])) {
    if (isset($_COOKIE['encuesta_votos'])) {
        $datosCookie = json_decode($_COOKIE['encuesta_votos'], true);
        $_SESSION['votos_si'] = $datosCookie['si'] ?? 0;
        $_SESSION['votos_no'] = $datosCookie['no'] ?? 0;
    } else {
        $_SESSION['votos_si'] = 0;
        $_SESSION['votos_no'] = 0;
    }
}

// Registro del voto enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['voto'])) {
    if ($_POST['voto'] === 'si') {
        $_SESSION['votos_si']++;
    } elseif ($_POST['voto'] === 'no') {
        $_SESSION['votos_no']++;
    }

    // Persistir el resultado en la cookie
    $datosCookie = json_encode([
        'si' => $_SESSION['votos_si'],
        'no' => $_SESSION['votos_no']
    ]);
    setcookie('encuesta_votos', $datosCookie, time() + TIEMPO_COOKIE);
}

$votosSi = $_SESSION['votos_si'];
$votosNo = $_SESSION['votos_no'];
$total = $votosSi + $votosNo;

$pctSi = $total > 0 ? round(($votosSi / $total) * 100) : 0;
$pctNo = $total > 0 ? round(($votosNo / $total) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 2 - Encuesta</title>
    <style>
        .contenedor-barra { width: 300px; background-color: #e0e0e0; border: 1px solid #999; margin: 5px 0; }
        .barra-si { width: <?= $pctSi ?>%; background-color: #28a745; height: 22px; color: #fff; text-align: right; padding-right: 5px; box-sizing: border-box; }
        .barra-no { width: <?= $pctNo ?>%; background-color: #dc3545; height: 22px; color: #fff; text-align: right; padding-right: 5px; box-sizing: border-box; }
    </style>
</head>
<body>
    <h3>¿Crees que actualmente hay corrupción en el gobierno?</h3>

    <form action="encuesta.php" method="post">
        <button type="submit" name="voto" value="si">SÍ</button>
        <button type="submit" name="voto" value="no">NO</button>
    </form>

    <br>
    <div>
        <strong>SÍ (<?= $votosSi ?> votos - <?= $pctSi ?>%):</strong>
        <div class="contenedor-barra">
            <div class="barra-si"><?= $pctSi > 0 ? $pctSi . '%' : '' ?></div>
        </div>
    </div>

    <div>
        <strong>NO (<?= $votosNo ?> votos - <?= $pctNo ?>%):</strong>
        <div class="contenedor-barra">
            <div class="barra-no"><?= $pctNo > 0 ? $pctNo . '%' : '' ?></div>
        </div>
    </div>
</body>
</html>