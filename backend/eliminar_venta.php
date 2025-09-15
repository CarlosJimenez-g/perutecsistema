<?php
include("conexion.php");
$conn = conexion();

// Validar si llegó el ID por GET
if (isset($_GET['id'])) {
    $venta_id = intval($_GET['id']);

    // Eliminar los detalles de la venta primero
    $sql_detalle = "DELETE FROM detalle_venta WHERE id_venta = ?";
    $stmt_detalle = $conn->prepare($sql_detalle);
    $stmt_detalle->bind_param("i", $venta_id);
    $stmt_detalle->execute();
    $stmt_detalle->close();

    // Luego eliminar la venta
    $sql_venta = "DELETE FROM venta WHERE id = ?";
    $stmt_venta = $conn->prepare($sql_venta);
    $stmt_venta->bind_param("i", $venta_id);
    $stmt_venta->execute();
    $stmt_venta->close();

    // Eliminar el PDF si existe
    $pdf_path = "../pdf_ventas/venta_" . $venta_id . ".pdf";
    if (file_exists($pdf_path)) {
        unlink($pdf_path);
    }

    // Redirigir con mensaje
    header("Location: ../administrador/ventas_hechas.php?msg=eliminado");
    exit;
} else {
    echo "ID de venta no válido.";
}
?>
