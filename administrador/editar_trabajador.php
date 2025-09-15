<?php
include '../backend/conexion.php';
$conn = conexion();

if (!isset($_GET['id'])) {
    die('ID no proporcionado.');
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM trabajadores WHERE Id = $id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0) {
    die('Trabajador no encontrado.');
}

$trabajador = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Trabajador</title>
    <link rel="stylesheet" href="../css/productos.css">
</head>
<body>
<center>
    <h2>Editar Trabajador</h2>
    <form action="../backend/actualizar_trabajador.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $trabajador['Id']; ?>">
        <table border="1" cellpadding="10">
            <tr>
                <th>Nombre</th>
                <td><input type="text" name="nombre" value="<?php echo $trabajador['Nombre']; ?>" required></td>
            </tr>
            <tr>
                <th>Apellidos</th>
                <td><input type="text" name="apellidos" value="<?php echo $trabajador['Apellidos']; ?>" required></td>
            </tr>
            <tr>
                <th>Correo</th>
                <td><input type="email" name="correo" value="<?php echo $trabajador['Correo']; ?>" required></td>
            </tr>
            <tr>
                <th>Celular</th>
                <td><input type="text" name="celular" value="<?php echo $trabajador['Celular']; ?>" required></td>
            </tr>
            <tr>
                <th>DNI</th>
                <td><input type="text" name="dni" value="<?php echo $trabajador['DNI']; ?>" required></td>
            </tr>
            <tr>
                <th>Dirección</th>
                <td><input type="text" name="direccion" value="<?php echo $trabajador['direccion']; ?>" required></td>
            </tr>
            <tr>
                <th>Casa por Google Maps</th>
                <td><input type="text" name="casa_google" value="<?php echo $trabajador['casa_google']; ?>" required></td>
            </tr>
            <tr>
                <th>Fecha de nacimiento</th>
                <td><input type="date" name="fecha_nacimiento" value="<?php echo $trabajador['fecha_nacimiento']; ?>" required></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <button type="submit">Actualizar</button>
                    <a href="trabajadores.php"><button type="button">Cancelar</button></a>
                </td>
            </tr>
        </table>
    </form>
</center>
</body>
</html>
<?php mysqli_close($conn); ?>
