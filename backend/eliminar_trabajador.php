<?php
include 'conexion.php';
$conn = conexion();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "DELETE FROM trabajadores WHERE Id = $id";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Trabajador eliminado correctamente'); window.location.href='../administrador/trabajadores.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "ID no proporcionado.";
}

mysqli_close($conn);
?>
