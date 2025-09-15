<?php
session_start();
include("conexion.php");
$conn = conexion();

if (!empty($_POST["ingresar"])) {
    if (empty($_POST["nombre"]) || empty($_POST["contrasena"])) {
        echo '<div class="alert alert-danger">LOS CAMPOS ESTÁN VACÍOS</div>';
    } else {
        $nombre = $_POST["nombre"];
        $contrasena = $_POST["contrasena"];

        // CONSULTA SEGURA CON PREPARED STATEMENT
        $stmt = $conn->prepare("SELECT * FROM usuario WHERE nombre = ? AND contrasena = ?");
        $stmt->bind_param("ss", $nombre, $contrasena);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($datos = $resultado->fetch_assoc()) {
            $_SESSION['nombre_usuario'] = $datos["nombre"];
            $_SESSION['rol'] = $datos["rol"];

            if ($datos["rol"] == 'admin') {
                header("Location: administrador/producto_vista.php");
                exit();
            } elseif ($datos["rol"] == 'usuario') {
                header("Location: usuario/productos_usuario.php");
                exit();
            }
        } else {
            echo '<div class="alert alert-danger">ACCESO DENEGADO: Usuario o contraseña incorrecta</div>';
        }

        $stmt->close();
        $conn->close();
    }
}
?>
