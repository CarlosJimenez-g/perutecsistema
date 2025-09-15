<?php
include 'conexion.php';
$conn = conexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $apellidos = mysqli_real_escape_string($conn, $_POST['apellidos']);
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $celular = mysqli_real_escape_string($conn, $_POST['celular']);
    $dni = mysqli_real_escape_string($conn, $_POST['dni']);
    $direccion = mysqli_real_escape_string($conn, $_POST['direccion']);
    $casa_google = mysqli_real_escape_string($conn, $_POST['casa_google']);
    $fecha_nacimiento = mysqli_real_escape_string($conn, $_POST['fecha_nacimiento']);

    $sql = "INSERT INTO trabajadores (Nombre, Apellidos, Correo, Celular, DNI, direccion, casa_google, fecha_nacimiento)
            VALUES ('$nombre', '$apellidos', '$correo', '$celular', '$dni', '$direccion', '$casa_google', '$fecha_nacimiento')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Trabajador registrado con éxito'); window.location.href='../administrador/trabajadores.php';</script>";
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
} else {
    echo 'Método no permitido.';
}

mysqli_close($conn);
?>
