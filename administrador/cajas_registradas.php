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

// Calcular totales diarios y general
$resumen_diario = [];
$total_general = 0;

foreach ($datos_por_fecha as $fecha => $cierres) {
    $total_dia = 0;
    $usado_todo_dia = false;

    foreach ($cierres as $cierre) {
        if ($cierre['turno'] === 'Todo el día') {
            $total_dia = $cierre['total_caja'];
            $usado_todo_dia = true;
            break;
        }
    }

    if (!$usado_todo_dia) {
        foreach ($cierres as $cierre) {
            $total_dia += $cierre['total_caja'];
        }
    }

    $resumen_diario[$fecha] = $total_dia;
    $total_general += $total_dia;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Historial de Cierres de Caja</title>
    <link rel="stylesheet" href="../css/estilo_venta.css">
    <link rel="stylesheet" href="../css/barra_navegacion.css">
</head>
<body>
<center>
    <?php include("capas/barra_navegacion.php"); ?>

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
        <button style="background-color:#28a745;color:white;padding:5px 10px;border:none;border-radius:5px;cursor:pointer;">
            📄 Generar PDF Resumen
        </button>
    </a>
    </form>
    <?php endif; ?>

    <!-- RESUMEN DIARIO -->
    <div style="margin-bottom: 30px; border: 2px solid #ccc; padding: 15px; width: 80%;">
        <h2>📊 Resumen Diario de Caja</h2>
        <table border="1" cellpadding="8">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th>Fecha</th>
                    <th>Total del Día (S/)</th>
                    <th>Ir a Detalles</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resumen_diario as $fecha => $total): ?>
                    <tr>
                        <td><?= htmlspecialchars($fecha) ?></td>
                        <td><strong>S/ <?= number_format($total, 2) ?></strong></td>
                        <td><a href="#detalles_<?= $fecha ?>" style="font-size: 0.9em;">🔍 Ver detalles</a></td>
                    </tr>
                <?php endforeach; ?>
                <tr style="background-color: #dff0d8;">
                    <td><strong>Total General</strong></td>
                    <td colspan="2"><strong>S/ <?= number_format($total_general, 2) ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>

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
                <th>Acciones</th>
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
                    <td>
                        <!-- Botón PDF -->
                        <a href="#">
                            <button style="background-color: #007bff; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;">
                                📄 Generar PDF
                            </button>
                        </a>
                        <br><br>
                        <!-- Botón Eliminar -->
                        <a href="../backend/eliminar_cierre.php?id=<?= $row['id'] ?>"
                           onclick="return confirm('⚠️ ¿Estás seguro de eliminar este cierre de caja? Esta acción no se puede deshacer.');">
                            <button style="background-color: #dc3545; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;">
                                🗑️ Eliminar Cierre
                            </button>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</center>
</body>
</html>
