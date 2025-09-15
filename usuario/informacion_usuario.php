<?php
include '../backend/conexion.php';
$conn = conexion();
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
    <h2><center>MÁS DE PERUTEC</center></h2><br>
    <div class="main-container">
    <h1>INFO AQUIII</h1>
    <p><strong>TRABAJOS DE FOTOGRAFÍAS Y SERVICVIOS</strong></p>
    <div class="seccion-contenedor">

        <!-- Sección Tamaño de Fotos -->
        <div class="seccion">
            <h3>10x15 - JUMBO</h3>
            <table>
                <tr><th>Cantidad</th><th>Precio Unitario (S/.)</th></tr>
                <tr><td>1 Unidad</td><td>2.00</td></tr>
                <tr><td>12 Unidades</td><td>1.00</td></tr>
                <tr><td>30 Unidades</td><td>0.80</td></tr>
                <tr><td>50 Unidades</td><td>0.60</td></tr>
            </table>

            <h3>A5 (15X20 CM) o 13X18 cm</h3>
            <table>
                <tr><th>Cantidad</th><th>Precio Unitario (S/.)</th></tr>
                <tr><td>1 Unidad</td><td>5.00</td></tr>
                <tr><td>3 Unidades</td><td>4.00</td></tr>
                <tr><td>6 Unidades</td><td>3.50</td></tr>
                <tr><td>12 Unidades</td><td>3.00</td></tr>
            </table>

            <h3>A4 (21x30 cm)</h3>
            <table>
                <tr><th>Cantidad</th><th>Precio Unitario (S/.)</th></tr>
                <tr><td>1 Unidad</td><td>10.00</td></tr>
                <tr><td>3 Unidades</td><td>8.00</td></tr>
                <tr><td>6 Unidades</td><td>7.00</td></tr>
                <tr><td>12 Unidades</td><td>5.00</td></tr>
            </table>

            <h3>A3 (30x42 cm)</h3>
            <table>
                <tr><th>Cantidad</th><th>Precio Unitario (S/.)</th></tr>
                <tr><td>1 Unidad</td><td>20.00</td></tr>
                <tr><td>3 Unidades</td><td>18.00</td></tr>
                <tr><td>6 Unidades</td><td>16.00</td></tr>
                <tr><td>12 Unidades</td><td>14.00</td></tr>
            </table>

            <h3>A3+ (33X48 cm)</h3>
            <table>
                <tr><th>Cantidad</th><th>Precio Unitario (S/.)</th></tr>
                <tr><td>1 Unidad</td><td>25.00</td></tr>
                <tr><td>3 Unidades</td><td>20.00</td></tr>
                <tr><td>6 Unidades</td><td>18.00</td></tr>
                <tr><td>12 Unidades</td><td>15.00</td></tr>
            </table>
        </div>

        <!-- Sección Llaveros -->
        <div class="seccion">
            <h3>LLAVEROS</h3>
            <p>Precios de los llaveros con la misma foto</p>
            <table>
                <tr><th>Cantidad</th><th>Precio Unitario (S/.)</th></tr>
                <tr><td>1 Unidad</td><td>6.00</td></tr>
                <tr><td>6 Unidades</td><td>5.00</td></tr>
                <tr><td>12 Unidades</td><td>4.50</td></tr>
                <tr><td>30 Unidades</td><td>4.00</td></tr>
                <tr><td>50 a más</td><td>3.50</td></tr>
            </table>
            <p class="nota">
                <strong>Nota:</strong><br>
                A partir de 12 unidades no se cobra diseño.<br>
                Si lleva menos de 12 unidades y desea un diseño, se cobra dependiendo de la dificultad: S/5.00.
            </p>
        </div>
    </div>
</div>
</center>
</body>
</html>
