<?php
require('fpdf/fpdf.php'); // asegúrate que la librería FPDF esté en esta ruta
include("conexion.php");
$conn = conexion();

$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

// Construir consulta SQL
if ($fecha_inicio && $fecha_fin) {
    $sql_ventas = "SELECT * FROM venta WHERE DATE(fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin' ORDER BY fecha DESC";
} elseif ($fecha_inicio) {
    $sql_ventas = "SELECT * FROM venta WHERE DATE(fecha) = '$fecha_inicio' ORDER BY fecha DESC";
} else {
    $sql_ventas = "SELECT * FROM venta ORDER BY fecha DESC";
}

$result_ventas = $conn->query($sql_ventas);

// Inicializar
$total_ventas_dia = 0;
$monto_total_dia = 0.00;
$productos_vendidos = [];
$ventas_por_vendedor = [];  
$ventas = [];

while ($venta = $result_ventas->fetch_assoc()) {
    $ventas[] = $venta;
    $total_ventas_dia++;
    $monto_total_dia += $venta['total'];

    // acumular por vendedor
    $vendedor = $venta['nombre_vendedor'];
    if (!isset($ventas_por_vendedor[$vendedor])) $ventas_por_vendedor[$vendedor] = 0;
    $ventas_por_vendedor[$vendedor] += $venta['total'];

    // acumular productos
    $venta_id = $venta['id'];
    $sql_detalle = "SELECT * FROM detalle_venta WHERE id_venta = $venta_id";
    $result_detalle = $conn->query($sql_detalle);
    while ($detalle = $result_detalle->fetch_assoc()) {
        $producto = $detalle['nombre_producto'];
        $cantidad = $detalle['cantidad'];
        if (!isset($productos_vendidos[$producto])) $productos_vendidos[$producto] = 0;
        $productos_vendidos[$producto] += $cantidad;
    }
}

// producto más vendido
$producto_mas_vendido = null;
$cantidad_mas_vendida = 0;
if (!empty($productos_vendidos)) {
    arsort($productos_vendidos);
    $producto_mas_vendido = key($productos_vendidos);
    $cantidad_mas_vendida = current($productos_vendidos);
}

// mejor vendedor
$mejor_vendedor = null;
$monto_mejor_vendedor = 0;
if (!empty($ventas_por_vendedor)) {
    arsort($ventas_por_vendedor);
    $mejor_vendedor = key($ventas_por_vendedor);
    $monto_mejor_vendedor = current($ventas_por_vendedor);
}

// CREAR PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);

// Título
$pdf->Cell(0,10,'Reporte de Ventas',0,1,'C');
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,'Periodo: '.($fecha_inicio ? $fecha_inicio : 'Todas').' - '.($fecha_fin ? $fecha_fin : 'Todas'),0,1,'C');
$pdf->Ln(5);

// Resumen
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,'Resumen:',0,1);
$pdf->SetFont('Arial','',11);
$pdf->Cell(0,8,'Total de Ventas: '.$total_ventas_dia,0,1);
$pdf->Cell(0,8,'Monto Total Vendido: S/ '.number_format($monto_total_dia,2),0,1);
if ($producto_mas_vendido) $pdf->Cell(0,8,'Producto mas vendido: '.$producto_mas_vendido.' ('.$cantidad_mas_vendida.' unidades)',0,1);
if ($mejor_vendedor) $pdf->Cell(0,8,'Mejor vendedor: '.$mejor_vendedor.' (S/ '.number_format($monto_mejor_vendedor,2).')',0,1);
$pdf->Ln(10);

// Detalles de ventas
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,'Detalles de Ventas:',0,1);

foreach ($ventas as $venta) {
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(0,8,'Venta ID: '.$venta['id'].' - Cliente: '.$venta['nombre_cliente'].' - Vendedor: '.$venta['nombre_vendedor'],0,1);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,6,'Fecha: '.$venta['fecha'].'  |  Total: S/ '.number_format($venta['total'],2),0,1);

    // Tabla de productos
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(40,6,'Codigo',1);
    $pdf->Cell(60,6,'Producto',1);
    $pdf->Cell(20,6,'Cant.',1);
    $pdf->Cell(30,6,'Precio',1);
    $pdf->Cell(30,6,'Subtotal',1);
    $pdf->Ln();

    $pdf->SetFont('Arial','',9);
    $venta_id = $venta['id'];
    $sql_detalle = "SELECT * FROM detalle_venta WHERE id_venta = $venta_id";
    $result_detalle = $conn->query($sql_detalle);
    while ($detalle = $result_detalle->fetch_assoc()) {
        $pdf->Cell(40,6,$detalle['codigo_producto'],1);
        $pdf->Cell(60,6,$detalle['nombre_producto'],1);
        $pdf->Cell(20,6,$detalle['cantidad'],1,0,'C');
        $pdf->Cell(30,6,'S/ '.number_format($detalle['precio_unitario'],2),1,0,'R');
        $pdf->Cell(30,6,'S/ '.number_format($detalle['subtotal'],2),1,0,'R');
        $pdf->Ln();
    }
    $pdf->Ln(5);
}

$pdf->Output('I','reporte_ventas.pdf');
?>
