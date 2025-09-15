<?php
include '../backend/conexion.php';
$conn = conexion();

// Buscar por código, nombre o descripción
$busqueda = "";
if (isset($_GET['buscar'])) {
    $busqueda = trim($_GET['buscar']);
    $sql = "SELECT * FROM producto 
            WHERE codigo LIKE '%$busqueda%' 
            OR nombre LIKE '%$busqueda%' 
            OR descripcion LIKE '%$busqueda%'";
} else {
    $sql = "SELECT * FROM producto";
}


$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PERUTEC</title>
    <link rel="stylesheet" href="../css/productos.css">
    <link href="../css/barra_navegacion.css" rel="stylesheet" type="text/css">
    <script src="../js/libreria.js" type="text/javascript"></script>
    <link rel="stylesheet" href="../css/agrandar_imagen.css">
    <script src="../js/imagen_producto.js" type="text/javascript"></script>
</head>
<body>
<center>
        <?php include("capas/barra_navegacion.php"); ?>
        <h2>
                <Center>BUSCAR PRODUCTO</Center>
            </h2><br>
    <div class="main-container">
        <h1>Catálogo de Productos</h1>

        <div class="search-box">
            <form method="GET">
                <input type="text" name="buscar" placeholder="Buscar por código, nombre o descripción" value="<?= htmlspecialchars($busqueda) ?>">
                <button type="submit">Buscar</button>
            </form>
        </div>  

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Imagen</th>
                    </tr>
                    <?php $total_stock = 0; ?>
                </thead>
                <tbody>
                    <?php if ($resultado->num_rows > 0): ?>
                        <?php while($row = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['codigo'] ?></td>
                            <td><?= $row['nombre'] ?></td>
                            <td><?= $row['descripcion'] ?></td>
                            <td>S/ <?= number_format($row['precio_venta'], 2) ?></td>
                            <td><?= $row['stock_actual'] ?></td>
                            <td>
                                <?php if (!empty($row['imagen'])): ?>
                                    <img src="<?= $row['imagen'] ?>" alt="Imagen" width="80" style="cursor: pointer;" onclick="mostrarImagenGrande(this.src)">
                                <?php else: ?>
                                    <span class="no-img">Sin imagen</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php $total_stock += $row['stock_actual']; ?>
                        <?php endwhile; ?>
                        <tr>
    <td colspan="6"><strong>Total de stock:</strong></td>
    <td><strong><?= $total_stock ?></strong></td>
    <td colspan="3"></td>
</tr>
                    <?php else: ?>
                        <tr><td colspan="6">No se encontraron productos</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
        </div>
        
    </div>
    <div id="imagenModal" class="modal">
  <span class="close" onclick="cerrarModal()">&times;</span>
  <img class="modal-content" id="imagenGrande">
</div>
</body>
</html>
