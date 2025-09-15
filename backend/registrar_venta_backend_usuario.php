<?php
session_start();
include("conexion.php");
require("fpdf/fpdf.php"); // asegúrate de tener la librería en backend/fpdf/
$conn = conexion();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_cliente = $_POST['nombre_cliente'];
    $n_celular_cliente = $_POST['n_celular_cliente'];
    $nombre_vendedor = $_POST['nombre_vendedor'];
    $fecha = $_POST['fecha'];
    $descuento = isset($_POST['descuento_valor']) ? floatval($_POST['descuento_valor']) : 0;

    // NUEVOS CAMPOS
    $costo_adicional = isset($_POST['costo_adicional_valor']) ? floatval($_POST['costo_adicional_valor']) : 0;
    $detalle_costo_adicional = isset($_POST['detalle_costo_adicional']) ? trim($_POST['detalle_costo_adicional']) : '';

    $carrito = json_decode($_POST['carrito_data'], true);

    if (empty($carrito)) {
        die("El carrito está vacío.");
    }

    // Insertar venta con total temporal = 0
    $stmt = $conn->prepare("INSERT INTO venta (nombre_cliente, n_celular_cliente, nombre_vendedor, fecha, total, descuento, costo_adicional, detalle_costo_adicional)
                            VALUES (?, ?, ?, ?, 0, ?, ?, ?)");
    $stmt->bind_param("ssssdds", $nombre_cliente, $n_celular_cliente, $nombre_vendedor, $fecha, $descuento, $costo_adicional, $detalle_costo_adicional);
    $stmt->execute();
    $id_venta = $stmt->insert_id;
    $stmt->close();

    // Calcular total real
    $total_real = 0;
    foreach ($carrito as $item) {
        $codigo = $item['codigo'];
        $nombre = $item['nombre'];
        $cantidad = intval($item['cantidad']);
        $precio = floatval($item['precio_unitario']);

        // Verificar stock
        $stmt = $conn->prepare("SELECT stock_actual FROM producto WHERE codigo = ?");
        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$res || $res['stock_actual'] < $cantidad || $cantidad <= 0) {
            continue; // Saltar productos inválidos
        }

        $subtotal = $cantidad * $precio;
        $total_real += $subtotal;

        // Insertar detalle
        $stmt = $conn->prepare("INSERT INTO detalle_venta (id_venta, codigo_producto, nombre_producto, cantidad, precio_unitario, subtotal)
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issidd", $id_venta, $codigo, $nombre, $cantidad, $precio, $subtotal);
        $stmt->execute();
        $stmt->close();

        // Actualizar stock
        $stmt = $conn->prepare("UPDATE producto SET stock_actual = stock_actual - ? WHERE codigo = ?");
        $stmt->bind_param("is", $cantidad, $codigo);
        $stmt->execute();
        $stmt->close();
    }

    // Aplicar descuento y costo adicional
    $total_final = $total_real - $descuento + $costo_adicional;
    if ($total_final < 0) $total_final = 0;

    // Actualizar venta con total real
    $stmt = $conn->prepare("UPDATE venta SET total = ? WHERE id = ?");
    $stmt->bind_param("di", $total_final, $id_venta);
    $stmt->execute();
    $stmt->close();

    // ================== CREAR PDF ==================
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(0,0,0); // Negro

// ==== ENCABEZADO ====
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,utf8_decode('RECIBO'),0,1,'R');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,utf8_decode('RUC: 10441671020'),0,1,'R');

// ==== FECHA ====
$pdf->Ln(4);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(30,8,utf8_decode('DÍA'),1,0,'C');
$pdf->Cell(30,8,utf8_decode('MES'),1,0,'C');
$pdf->Cell(30,8,utf8_decode('AÑO'),1,1,'C');
$fecha_partes = explode('-', $fecha); // formato esperado: YYYY-MM-DD
$pdf->SetFont('Arial','',10);
$pdf->Cell(30,8,isset($fecha_partes[2])?$fecha_partes[2]:'',1,0,'C');
$pdf->Cell(30,8,isset($fecha_partes[1])?$fecha_partes[1]:'',1,0,'C');
$pdf->Cell(30,8,isset($fecha_partes[0])?$fecha_partes[0]:'',1,1,'C');

// ==== DATOS EMPRESA ====
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6,utf8_decode('Av. del Muro Mz. M9 Lote 30 Urb. Mariscal Cáceres - S.J.L.'),0,1);
$pdf->Cell(0,6,utf8_decode('e-mail: empresa.perutec@gmail.com  |  @PeruTecFotos'),0,1);
$pdf->MultiCell(0,6,utf8_decode("Te ofrecemos: Venta de artículos tecnológicos, estudio fotográfico, revelados de fotos,\nampliaciones, carnet, pasaporte, visa, cuadros, ediciones y montajes, copias e impresiones."),0,'L');

// ==== CLIENTE ====
$pdf->Ln(4);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,utf8_decode("Sr(a).: $nombre_cliente"),0,1);
$pdf->Cell(0,8,utf8_decode("N° de Celular de Cliente: $n_celular_cliente"),0,1);
$pdf->Ln(3);

// ==== TABLA DE PRODUCTOS ====
$pdf->SetFont('Arial','B',12);
$pdf->Cell(30,8,utf8_decode('CANT.'),1,0,'C');
$pdf->Cell(90,8,utf8_decode('DESCRIPCIÓN'),1,0,'C');
$pdf->Cell(35,8,utf8_decode('P.UNIT'),1,0,'C');
$pdf->Cell(35,8,utf8_decode('TOTAL'),1,1,'C');

$pdf->SetFont('Arial','',12);
foreach ($carrito as $item) {
    $cantidad = $item['cantidad'];
    $descripcion = $item['nombre'];
    $precio_unitario = $item['precio_unitario'];
    $subtotal = $cantidad * $precio_unitario;

    $pdf->Cell(30,8,$cantidad,1,0,'C');
    $pdf->Cell(90,8,utf8_decode($descripcion),1,0);
    $pdf->Cell(35,8,number_format($precio_unitario,2),1,0,'R');
    $pdf->Cell(35,8,number_format($subtotal,2),1,1,'R');
}

// ==== DETALLE DE DESCUENTO Y COSTO ADICIONAL ====
if ($descuento > 0) {
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(155,8,utf8_decode('Descuento'),1,0,'R');
    $pdf->Cell(35,8,'- S/ '.number_format($descuento,2),1,1,'R');
}

if ($costo_adicional > 0 && $detalle_costo_adicional !== '') {
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(155,8,utf8_decode($detalle_costo_adicional),1,0,'R');
    $pdf->Cell(35,8,'+ S/ '.number_format($costo_adicional,2),1,1,'R');
}

// ==== TOTAL FINAL ====
$pdf->SetFont('Arial','B',12);
$pdf->Cell(155,8,utf8_decode('TOTAL'),1,0,'R');
$pdf->Cell(35,8,'S/ '.number_format($total_final,2),1,1,'R');

// ==== GUARDAR PDF ====
$carpeta = "../pdf_ventas/";
if (!file_exists($carpeta)) {
    mkdir($carpeta,0777,true);
}
$ruta_pdf = $carpeta."venta_".$id_venta.".pdf";
$pdf->Output("F",$ruta_pdf);

    // ==== REDIRECCIÓN ====
    header("Location: ../usuario/venta_exitosa_usuario.php?id=$id_venta");
exit();
}
?>
