<?php
include '../backend/conexion.php';
$conn = conexion();

$sql = "SELECT * FROM trabajadores";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PERUTEC</title>
    <link rel="icon" type="image/png" href="../imagenes/logo_perutec.png">
    <link rel="stylesheet" href="../css/productos.css">
    <link href="../css/barra_navegacion.css" rel="stylesheet" type="text/css">
    <link href="../css/estilo_informacion.css" rel="stylesheet" type="text/css"> 
    
</head>
<body>
<center>
    <?php include("capas_usuario/barra_navegacion_usuario.php"); ?>
    <h2>LISTA DE TRABAJADORES</h2><br>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Correo</th>
            <th>Celular</th>
            <th>DNI</th>
            <th>Dirección</th>
            <th>Casa por Maps</th>
            <th>Fecha Nacimiento</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['Id']; ?></td>
            <td><?php echo $row['Nombre']; ?></td>
            <td><?php echo $row['Apellidos']; ?></td>
            <td><?php echo $row['Correo']; ?></td>
            <td><?php echo $row['Celular']; ?></td>
            <td><?php echo $row['DNI']; ?></td>
            <td><?php echo $row['direccion']; ?></td>
            <td><?php echo $row['casa_google']?></td>
            <td><?php echo $row['fecha_nacimiento']; ?></td>
        </tr>
        <?php } ?>
    </table>
</center>
</body>
</html>
<?php mysqli_close($conn); ?>
