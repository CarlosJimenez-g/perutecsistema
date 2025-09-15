<?php include("../backend/producto_crud.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>  
    <meta charset="UTF-8">
    <title>PERUTEC</title>
    <link href="../css/barra_navegacion.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../css/tablaproducto.css">
    <link rel="stylesheet" href="../css/agrandar_imagen.css">
    <script src="../js/libreria.js" type="text/javascript"></script>
    <script src="../js/imagen_producto.js" type="text/javascript"></script>
</head>
<body>
<center>
<?php include("capas/barra_navegacion.php"); ?>

<?php if (isset($_GET['msg'])): ?>
    <p style="color: green; font-weight: bold;">
        <?= htmlspecialchars($_GET['msg']) ?>
    </p>
<?php endif; ?>

<h2>INGRESAR PRODUCTO</h2>

<a href="../backend/producto_agregar.php"><button>Agregar Producto</button></a><br><br>

<!-- FORMULARIO DE BÚSQUEDA Y FILTRO -->
<form method="POST">
    <input type="text" name="search" placeholder="Buscar producto" value="<?php echo $search ?? ''; ?>">
    <input type="number" name="stock_min" placeholder="Stock máximo" min="0" value="<?php echo $_POST['stock_min'] ?? ''; ?>">
    <button type="submit">Buscar</button>
</form>

<table border="1" cellpadding="5">
    <tr>
        <th>Código</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Precio Compra</th>
        <th>Precio Venta</th>
        <th>Ganancia</th>
        <th>Stock</th>
        <th>Imagen</th>
        <th>Fecha Ingreso</th>
        <th>Última Actualización</th>
        <th>Tiempo en Inventario</th>
        <th>Eliminar</th>
        <th>Actualizar</th>
    </tr>   

    <?php $total_stock = 0; ?>

    <?php while($row = $productos->fetch_assoc()): ?>
        <?php
            $fechaIngreso = new DateTime($row['fecha_ingreso']);
            $hoy = new DateTime();
            $intervalo = $fechaIngreso->diff($hoy);
            $diasTotales = $intervalo->days;
        ?>
        <tr>
            <td><?= $row['codigo'] ?></td>
            <td><?= $row['nombre'] ?></td>
            <td><?= $row['descripcion'] ?></td>
            <td><?= $row['precio_compra'] ?></td>
            <td><?= $row['precio_venta'] ?></td>
            <td><?= $row['precio_venta'] - $row['precio_compra'] ?></td>
            <td><?= $row['stock_actual'] ?></td>
            <td>
                <?php if (!empty($row['imagen'])): ?>
                    <img src="<?= $row['imagen'] ?>" alt="Imagen" width="80" style="cursor: pointer;" onclick="mostrarImagenGrande(this.src)">
                <?php else: ?>
                    Sin imagen
                <?php endif; ?>
            </td>
            <td><?= $row['fecha_ingreso'] ?></td>
            <td><?= $row['ultima_actualizacion'] ?? 'Sin datos' ?></td>
            <td><?= $diasTotales ?> días</td>
            <td>
                <form method="POST" action="../backend/eliminar_producto.php">  
                    <input type="hidden" name="codigo" value="<?= $row['codigo'] ?>">
                    <input type="hidden" name="accion" value="eliminar">
                    <Center><button style="background-color: #dc3545; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;" type="submit">
                        🗑️Eliminar</button></Center>
                </form>
            </td>
            <td>
                <center><a href="producto_actualizar.php?codigo=<?= $row['codigo'] ?>"><button>Actualizar</button></a></center>
            </td>
        </tr>
        <?php $total_stock += $row['stock_actual']; ?>
    <?php endwhile; ?>

    <tr>
        <td colspan="6"><strong>Total de stock:</strong></td>
        <td><strong><?= $total_stock ?></strong></td>
        <td colspan="6"></td>
    </tr>
</table>

</center>

<!-- Modal para agrandar imagen -->
<div id="imagenModal" class="modal">
    <span class="close" onclick="cerrarModal()">&times;</span>
    <img class="modal-content" id="imagenGrande">
</div>
</body>
</html>
