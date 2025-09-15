<?php
include '../backend/conexion.php';
$conn = conexion();

// Obtener todos los trabajadores
$sql = "SELECT * FROM trabajadores";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PERUTEC</title>
    <link rel="stylesheet" href="../css/productos.css">
    <link href="../css/barra_navegacion.css" rel="stylesheet" type="text/css">
    <link href="../css/estilo_informacion.css" rel="stylesheet" type="text/css"> 
    
    <script>
        function confirmarEliminacion(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este trabajador?')) {
                window.location.href = '../backend/eliminar_trabajador.php?id=' + id;
            }
        }
    </script>
</head>
<body>
<center>
    <?php include("capas/barra_navegacion.php"); ?>
    <h2>TRABAJADORES</h2><br>

    <div class="main-container">
        <h1>Insertar trabajador</h1>
        <form action="../backend/registro_trabajador.php" method="POST">
            <table border="1" cellpadding="10">
                <tr>
                    <th>Nombre</th>
                    <td><input type="text" name="nombre" required></td>
                </tr>
                <tr>
                    <th>Apellidos</th>
                    <td><input type="text" name="apellidos" required></td>
                </tr>
                <tr>
                    <th>Correo</th>
                    <td><input type="email" name="correo" required></td>
                </tr>
                <tr>
                    <th>Celular</th>
                    <td><input type="number" name="celular" required></td>
                </tr>
                <tr>
                    <th>DNI</th>
                    <td><input type="number" name="dni" required></td>
                </tr>
                <tr>
                    <th>Dirección</th>
                    <td><input type="text" name="direccion" required></td>
                </tr>
                <tr>
                    <th>Link de casa</th>
                    <td><input type="text" name="direccion" required></td>
                </tr>
                <tr>
                    <th>Fecha de nacimiento</th>
                    <td><input type="date" name="fecha_nacimiento" required></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <button type="submit">Registrar</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <h2>Lista de Trabajadores</h2>
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
            <th>Acciones</th>
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
            <td>
    <button onclick="confirmarEliminacion(<?php echo $row['Id']; ?>)">Eliminar</button>
    <a href="../administrador/editar_trabajador.php?id=<?php echo $row['Id']; ?>">
        <button type="button">Editar</button>
    </a>
</td>
        </tr>
        <?php } ?>
    </table>
</center>
</body>
</html>
<?php mysqli_close($conn); ?>
