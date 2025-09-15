<?php
session_start();
include("../backend/conexion.php");
$conn = conexion();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PERUTEC</title>
    <link rel="icon" type="image/png" href="../imagenes/logo_perutec.png">
    <link rel="stylesheet" href="../css/estilos_mejorados_registrar_venta.css">
    <link rel="stylesheet" href="../css/productos.css">
    <link href="../css/barra_navegacion.css" rel="stylesheet" type="text/css">
    <script src="../js/carrito.js" defer></script>
    <script src="../js/libreria.js" type="text/javascript"></script>
</head>
<body>
    <?php include("capas_usuario/barra_navegacion_usuario.php"); ?>

    <h2 style="text-align:center;">REGISTRAR VENTA</h2>
    <form action="../backend/registrar_venta_backend_usuario.php" method="POST" onsubmit="return prepararDatos()">

        <label>Nombre del Cliente:</label>
        <input type="text" name="nombre_cliente" required>

        <label>N° de Celular:</label>
        <input type="text" name="n_celular_cliente" required>

        <label>Nombre del Vendedor:</label>
        <select name="nombre_vendedor" required>
            <option value="">-- Seleccione un trabajador --</option>
            <?php
            $trabajadores = $conn->query("SELECT Id, Nombre, Apellidos FROM trabajadores ORDER BY Nombre ASC");
            while ($row = $trabajadores->fetch_assoc()) {
                echo "<option value='".$row['Nombre']." ".$row['Apellidos']."'>".$row['Nombre']." ".$row['Apellidos']."</option>";
            }
            ?>
        </select>

        <label>Fecha:</label>
        <input type="text" name="fecha" id="fecha" readonly>

        <label>Buscar producto:</label>
        <input type="text" id="buscar" placeholder="Código o nombre..." onkeyup="filtrarProductos()">

        <!-- Tabla de productos -->
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Cantidad</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id="listaProductos">
                <?php
                $productos = $conn->query("SELECT * FROM producto");
                while($row = $productos->fetch_assoc()){
                    if ($row['stock_actual'] <= 0) {
                        echo "<tr style='color: gray; opacity: 0.6;'>";
                        echo "<td>".$row['codigo']."</td>";
                        echo "<td>".$row['nombre']."</td>";
                        echo "<td>".$row['descripcion']."</td>";
                        echo "<td>".$row['precio_venta']."</td>";
                        echo "<td>Sin stock</td>";
                        echo "<td><input type='number' value='0' disabled></td>";
                        echo "<td><button type='button' disabled>Sin stock</button></td>";
                        echo "</tr>";
                    } else {
                        echo "<tr>";
                        echo "<td>".$row['codigo']."</td>";
                        echo "<td>".$row['nombre']."</td>";
                        echo "<td>".$row['descripcion']."</td>";
                        echo "<td>".$row['precio_venta']."</td>";
                        echo "<td>".$row['stock_actual']."</td>";
                        echo "<td><input type='number' id='cant_".$row['codigo']."' min='1' max='".$row['stock_actual']."' value='1'></td>";
                        echo "<td><button type='button' onclick=\"agregarCarrito('".$row['codigo']."','".$row['nombre']."',".$row['precio_venta'].")\">Agregar</button></td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>

        <div id="carrito-flotante">
    <h3>🛒 Carrito</h3>
    <table>
        <thead>
            <tr>
                <th>Código</th> 
                <th>Nombre</th>
                <th>Cant.</th>
                <th>Unitario</th>
                <th>Subtotal</th>
                <th>Quitar</th>
            </tr>
        </thead>
        <tbody id="carrito"></tbody>
    </table>

    <label>Descuento (S/):</label>
    <input type="number" id="descuento" value="0" min="0" oninput="aplicarDescuento()">

    <label>Detalle del costo adicional:</label>
    <input type="text" id="detalle_costo_adicional" name="detalle_costo_adicional">

    <label>Costo adicional (S/):</label>
    <input type="number" id="costo_adicional" value="0" min="0" oninput="aplicarCostoAdicional()">

    <!-- Campos ocultos -->
    <input type="hidden" name="costo_adicional_valor" id="costo_adicional_valor">
    <input type="hidden" name="carrito_data" id="carrito_data">
    <input type="hidden" name="descuento_valor" id="descuento_valor">

    <h3>Total: S/ <span id="total">0</span></h3>
    <button type="submit">Registrar Venta</button>
    </form>
</div>


    

    <script>
        // Fecha actual en campo fecha
        document.getElementById("fecha").value = new Date().toISOString().split("T")[0];
    </script>
</body>
</html>
