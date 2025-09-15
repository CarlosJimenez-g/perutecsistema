<?php
include("../backend/conexion.php");
$conn = conexion();

// Obtener fechas desde el formulario
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

// Construir consulta SQL según si hay filtro
if ($fecha_inicio && $fecha_fin) {
    $sql_ventas = "SELECT * FROM venta WHERE DATE(fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin' ORDER BY fecha DESC";
} elseif ($fecha_inicio) {
    $sql_ventas = "SELECT * FROM venta WHERE DATE(fecha) = '$fecha_inicio' ORDER BY fecha DESC";
} else {
    $sql_ventas = "SELECT * FROM venta ORDER BY fecha DESC";
}

$result_ventas = $conn->query($sql_ventas);

// Inicializar resumen
$total_ventas_dia = 0;
$monto_total_dia = 0.00;

// Para estadísticas adicionales
$productos_vendidos = [];   // [nombre_producto => cantidad_total]
$ventas_por_vendedor = [];  // [nombre_vendedor => monto_total]

// Guardar ventas en array para procesar después
$ventas = [];
while ($venta = $result_ventas->fetch_assoc()) {
    $ventas[] = $venta;
    $total_ventas_dia++;
    $monto_total_dia += $venta['total'];

    // Acumular ventas por vendedor
    $vendedor = $venta['nombre_vendedor'];
    if (!isset($ventas_por_vendedor[$vendedor])) {
        $ventas_por_vendedor[$vendedor] = 0;
    }
    $ventas_por_vendedor[$vendedor] += $venta['total'];

    // Acumular productos vendidos
    $venta_id = $venta['id'];
    $sql_detalle = "SELECT * FROM detalle_venta WHERE id_venta = $venta_id";
    $result_detalle = $conn->query($sql_detalle);

    while ($detalle = $result_detalle->fetch_assoc()) {
        $producto = $detalle['nombre_producto'];
        $cantidad = $detalle['cantidad'];

        if (!isset($productos_vendidos[$producto])) {
            $productos_vendidos[$producto] = 0;
        }
        $productos_vendidos[$producto] += $cantidad;
    }
}

// Obtener producto más vendido
$producto_mas_vendido = null;
$cantidad_mas_vendida = 0;
if (!empty($productos_vendidos)) {
    arsort($productos_vendidos); // ordenar de mayor a menor
    $producto_mas_vendido = key($productos_vendidos);
    $cantidad_mas_vendida = current($productos_vendidos);
}

// Obtener mejor vendedor
$mejor_vendedor = null;
$monto_mejor_vendedor = 0;
if (!empty($ventas_por_vendedor)) {
    arsort($ventas_por_vendedor);
    $mejor_vendedor = key($ventas_por_vendedor);
    $monto_mejor_vendedor = current($ventas_por_vendedor);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ventas Realizadas</title>
    <link rel="stylesheet" href="../css/estilo_venta.css">
    <link rel="stylesheet" href="../css/estilo_ventas_hechas.css">
    <link href="../css/barra_navegacion.css" rel="stylesheet" type="text/css">
</head>
<body>
    <center>
        <?php include("capas/barra_navegacion.php"); ?>
        
        <h2><center>PROMOCIONES</center></h2>
        <h1>Ventas Realizadas</h1>

        <!-- Mensaje si se eliminó una venta -->
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'eliminado'): ?>
            <p style="color: green; font-weight: bold;">✅ Venta eliminada correctamente.</p>
        <?php endif; ?>

        <!-- FORMULARIO DE FILTRO -->
        <form method="GET" style="margin-bottom: 20px;">
            <label for="fecha_inicio">Desde:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?= htmlspecialchars($fecha_inicio) ?>">
            &nbsp;&nbsp;
            <label for="fecha_fin">Hasta:</label>
            <input type="date" id="fecha_fin" name="fecha_fin" value="<?= htmlspecialchars($fecha_fin) ?>">
            &nbsp;&nbsp;
            <button type="submit">Filtrar</button>
            &nbsp;&nbsp;
            <a href="ventas_hechas.php" style="text-decoration:none; color:red;">Limpiar Filtro</a>
        </form>

        <!-- RESUMEN DE VENTAS ARRIBA -->
        <?php if ($fecha_inicio || $fecha_fin || !empty($ventas)): ?>
            <div style="margin: 20px auto; border: 2px solid #ccc; padding: 15px; border-radius: 10px; width: 60%; background: #f9f9f9; text-align:left;">
                <h2>📊 Resumen <?= $fecha_fin ? "del $fecha_inicio al $fecha_fin" : ($fecha_inicio ? "del $fecha_inicio" : "de todas las ventas") ?></h2>
                <p><strong>🧾 Total de ventas:</strong> <?= $total_ventas_dia ?></p>
                <p><strong>💰 Monto total vendido:</strong> S/ <?= number_format($monto_total_dia, 2) ?></p>
                
                <?php if ($producto_mas_vendido): ?>
                    <p><strong>🏆 Producto más vendido:</strong> <?= htmlspecialchars($producto_mas_vendido) ?> (<?= $cantidad_mas_vendida ?> unidades)</p>
                <?php endif; ?>

                <?php if ($mejor_vendedor): ?>
                    <p><strong>👨‍💼 Mejor vendedor:</strong> <?= htmlspecialchars($mejor_vendedor) ?> (S/ <?= number_format($monto_mejor_vendedor, 2) ?> en ventas)</p>
                <?php endif; ?>
            </div>
            <!-- BOTÓN PARA GENERAR PDF -->
<form action="../backend/generar_reporte_ventas.php" method="GET" target="_blank">
    <input type="hidden" name="fecha_inicio" value="<?= $fecha_inicio ?>">
    <input type="hidden" name="fecha_fin" value="<?= $fecha_fin ?>">
    <button type="submit" style="background-color: green; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer;">
        📑 Generar PDF del Reporte
    </button>
</form>
        <?php endif; ?>

        <!-- LISTA DE VENTAS -->
        <?php foreach ($ventas as $venta): ?>
            <div class="venta">
                <h3>Venta ID: <?= $venta['id'] ?></h3>
                <p><strong>Cliente:</strong> <?= htmlspecialchars($venta['nombre_cliente']) ?> (N° de celular: <?= $venta['n_celular_cliente'] ?>)</p>
                <p><strong>Vendedor:</strong> <?= htmlspecialchars($venta['nombre_vendedor']) ?></p>
                <p><strong>Fecha:</strong> <?= $venta['fecha'] ?></p>
                <p><strong>Total:</strong> S/ <?= number_format($venta['total'], 2) ?></p>

                <?php
                    $venta_id = $venta['id'];
                    $sql_detalle = "SELECT * FROM detalle_venta WHERE id_venta = $venta_id";
                    $result_detalle = $conn->query($sql_detalle);
                ?>

                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre del Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($detalle = $result_detalle->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($detalle['codigo_producto']) ?></td>
                                <td><?= htmlspecialchars($detalle['nombre_producto']) ?></td>
                                <td><?= $detalle['cantidad'] ?></td>
                                <td>S/ <?= number_format($detalle['precio_unitario'], 2) ?></td>
                                <td>S/ <?= number_format($detalle['subtotal'], 2) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <?php
                    $ruta_pdf = "../pdf_ventas/venta_" . $venta['id'] . ".pdf";
                ?>
                <div style="margin-top: 10px;">
                    <?php if (file_exists($ruta_pdf)): ?>
                        <a href="<?= $ruta_pdf ?>" target="_blank">
                            <button style="background-color: #007bff; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;">
                                📄 Ver Comprobante
                            </button>
                        </a>
                    <?php else: ?>
                        <span style="color:red;">⚠️ Comprobante no generado</span>
                    <?php endif; ?>

                    &nbsp;

                    <a href="../backend/eliminar_venta.php?id=<?= $venta['id'] ?>" 
                    onclick="return confirm('⚠️ ¿Estás seguro de eliminar esta venta? Esta acción no se puede deshacer.');">
                        <button style="background-color: #dc3545; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;">
                            🗑️ Eliminar Venta
                        </button>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </center>
</body>
</html>
