<?php
include("conexion.php");
$conn = conexion();

if (isset($_GET['id'])) {
    $cierre_id = intval($_GET['id']);

    // Eliminar el cierre
    $sql = "DELETE FROM cierre_caja WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cierre_id);

    if ($stmt->execute()) {
        header("Location: ../administrador/cajas_registradas.php?msg=eliminado");
    } else {
        echo "❌ Error al eliminar el cierre: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "ID de cierre no válido.";
}
?>
