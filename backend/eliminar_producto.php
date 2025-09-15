<?php
include("conexion.php"); 
$conn = conexion();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $codigo = $_POST['codigo'] ?? null;

    if ($codigo) {
        // Prepara la sentencia para evitar inyecciones SQL
        $stmt = $conn->prepare("DELETE FROM productos WHERE codigo = ?");
        $stmt->bind_param("s", $codigo);

        if ($stmt->execute()) {
            // Redirigir con éxito
            header("Location: ../administrador/producto_vista.php?msg=Producto eliminado correctamente");
            exit();
        } else {
            echo "❌ Error al eliminar el producto: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "No se recibió un código de producto.";
    }
}

$conn->close();
?>
