<?php
require('fpdf/fpdf.php');
include("conexion.php");
$conn = conexion();

// Fechas del filtro
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

// Consulta SQL
if ($fecha_inicio && $fecha_fin) {
    $sql = "SELECT * FROM cierre_caja WHERE fecha BETWEEN '$fecha_inicio' AND '$fecha_fin' ORDER BY fecha DESC, registrado_en DESC";
} elseif ($fecha_inicio) {
    $sql = "SELECT * FROM cierre_caja WHERE fecha = '$fecha_inicio' ORDER BY fecha DESC, registrado_en DESC";
} else {
    $sql = "SELECT * FROM cierre_caja ORDER BY fecha DESC, registrado_en DESC";
}

$result = $conn->query($sql);

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);

// Título
$pdf->Cell(0,10,utf8_decode('Reporte de Cajas Registradas'),0,1,'C');
$pdf->Ln(5);

// Período
if ($fecha_inicio && $fecha_fin) {
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,10,utf8_decode("Periodo: $fecha_inicio hasta $fecha_fin"),0,1,'C');
} elseif ($fecha_inicio) {
    $pdf->Cell(0,10,utf8_decode("Fecha: $fecha_inicio"),0,1,'C');
} else {
    $pdf->Cell(0,10,utf8_decode("Historial completo"),0,1,'C');
}
$pdf->Ln(10);

// Encabezados de tabla
$pdf->SetFont('Arial','B',10);
$pdf->Cell(25,8,utf8_decode('Fecha'),1);
$pdf->Cell(35,8,utf8_decode('Trabajador'),1);
$pdf->Cell(25,8,utf8_decode('Turno'),1);
$pdf->Cell(20,8,utf8_decode('Base'),1);
$pdf->Cell(25,8,utf8_decode('Digital'),1);
$pdf->Cell(25,8,utf8_decode('Total Caja'),1);
$pdf->Cell(35,8,utf8_decode('Registrado En'),1);
$pdf->Ln();

// Datos
$pdf->SetFont('Arial','',9);
$total_general = 0;

while ($row = $result->fetch_assoc()) {
    $pdf->Cell(25,8,utf8_decode($row['fecha']),1);
    $pdf->Cell(35,8,utf8_decode($row['nombre_trabajador']),1);
    $pdf->Cell(25,8,utf8_decode($row['turno']),1);
    $pdf->Cell(20,8,'S/ '.number_format($row['monto_base'],2),1);
    $pdf->Cell(25,8,'S/ '.number_format($row['monto_digital'],2),1);
    $pdf->Cell(25,8,'S/ '.number_format($row['total_caja'],2),1);
    $pdf->Cell(35,8,utf8_decode($row['registrado_en']),1);
    $pdf->Ln();

    $total_general += $row['total_caja'];
}

// Total general
$pdf->SetFont('Arial','B',12);
$pdf->Cell(130,10,utf8_decode('TOTAL GENERAL'),1,0,'R');
$pdf->Cell(35,10,'S/ '.number_format($total_general,2),1,1,'C');

// Salida del PDF
$pdf->Output('I','reporte_cajas.pdf');
?>
