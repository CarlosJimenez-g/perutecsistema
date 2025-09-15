<?php
include 'conexion.php';
$conn = conexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $apellidos = mysqli_real_escape_string($conn, $_POST['apellidos']);
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $celular = mysqli_real_escape_string($conn, $_POST['celular']);
    $dni = mysqli_real_escape_string($conn, $_POST['dni']);
    $direccion = mysqli_real_escape_string($conn, $_POST['direccion']);
    $casa_google = mysqli_real_escape_string($conn, $_POST['casa_google']);
    $fecha_nacimiento = mysqli_real_escape_string($conn, $_POST['fecha_nacimiento']);

    $sql = "UPDATE trabajadores SET 
            Nombre = '$nombre',
            Apellidos = '$apellidos',
            Correo = '$correo',
            Celular = '$celular',
            DNI = '$dni',
            direccion = '$direccion',
            casa_google = '$casa_google',
            fecha_nacimiento = '$fecha_nacimiento'
            WHERE Id = $id";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Datos actualizados correctamente'); window.location.href='../administrador/trabajadores.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Método no permitido.";
}

mysqli_close($conn);
?>
