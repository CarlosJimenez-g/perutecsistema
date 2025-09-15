<?php
include("../backend/conexion.php");
$conn = conexion();

$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

// Construir consulta SQL con filtro
if ($fecha_inicio && $fecha_fin) {
    $sql = "SELECT * FROM cierre_caja WHERE fecha BETWEEN '$fecha_inicio' AND '$fecha_fin' ORDER BY fecha DESC, registrado_en DESC";
} elseif ($fecha_inicio) {
    $sql = "SELECT * FROM cierre_caja WHERE fecha = '$fecha_inicio' ORDER BY fecha DESC, registrado_en DESC";
} else {
    $sql = "SELECT * FROM cierre_caja ORDER BY fecha DESC, registrado_en DESC";
}

$result = $conn->query($sql);

// Organizar cierres por fecha
$datos_por_fecha = [];

while ($row = $result->fetch_assoc()) {
    $fecha = $row['fecha'];
    if (!isset($datos_por_fecha[$fecha])) {
        $datos_por_fecha[$fecha] = [];
    }
    $datos_por_fecha[$fecha][] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PERUTEC</title>
    <link rel="icon" type="image/png" href="../imagenes/logo_perutec.png">
    <link rel="stylesheet" href="../css/estilo_venta.css">
    <link rel="stylesheet" href="../css/estilo_cajas_registradas.css">
    <link rel="stylesheet" href="../css/barra_navegacion.css">
</head>
<body>
<center>
    <?php include("capas_usuario/barra_navegacion_usuario.php"); ?>

    <h1>Historial de Cierres de Caja</h1>

    <!-- FILTRO DE FECHAS -->
    <form method="GET" style="margin-bottom: 20px;">
        <label for="fecha_inicio">Desde:</label>
        <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($fecha_inicio) ?>">

        &nbsp;&nbsp;

        <label for="fecha_fin">Hasta:</label>
        <input type="date" name="fecha_fin" value="<?= htmlspecialchars($fecha_fin) ?>">

        &nbsp;&nbsp;

        <button type="submit">Filtrar</button>
        &nbsp;&nbsp;
        <a href="cajas_registradas.php" style="text-decoration: none; color: red;">Limpiar</a>
    </form>
    <?php if ($fecha_inicio || $fecha_fin): ?>
    <form method="GET" action="../backend/reporte_cajas.php" target="_blank" style="margin-bottom:20px;">
        <input type="hidden" name="fecha_inicio" value="<?= htmlspecialchars($fecha_inicio) ?>">
        <input type="hidden" name="fecha_fin" value="<?= htmlspecialchars($fecha_fin) ?>">
        <a href="../backend/reporte_cajas.php?fecha_inicio=<?= $fecha_inicio ?>&fecha_fin=<?= $fecha_fin ?>" target="_blank">
    </a>
    </form>
    <?php endif; ?>

    <!-- TABLA DETALLADA DE CIERRES -->
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Trabajador</th>
                <th>Turno</th>
                <th>Monto Base</th>
                <th>Monto Digital</th>
                <th>Observaciones</th>
                <th>Justificacion de Observaciones</th>
                <th>Total Caja</th>
                <th>Registrado En</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query($sql); // Ejecutamos de nuevo para mostrar detalles
            $ultima_fecha = null;
            while ($row = $result->fetch_assoc()):
                // Agregar ancla para "ver detalles" por fecha una sola vez
                if ($row['fecha'] !== $ultima_fecha):
                    echo "<tr id='detalles_{$row['fecha']}'><td colspan='8' style='background-color:#f9f9f9; font-weight:bold;'>📅 Detalles del día {$row['fecha']}</td></tr>";
                    $ultima_fecha = $row['fecha'];
                endif;
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['fecha']) ?></td>
                    <td><?= htmlspecialchars($row['nombre_trabajador']) ?></td>
                    <td><?= $row['turno'] ?></td>
                    <td>S/ <?= number_format($row['monto_base'], 2) ?></td>
                    <td>S/ <?= number_format($row['monto_digital'], 2) ?></td>
                    <td>S/ <?= number_format($row['observaciones'], 2) ?></td>
                    <td><?= htmlspecialchars($row['justificacion_observacion']) ?></td>
                    <td><strong>S/ <?= number_format($row['total_caja'], 2) ?></strong></td>
                    <td><?= $row['registrado_en'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</center>
</body>
</html>