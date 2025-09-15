<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link href="../css/barra_navegacion.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../css/tablaproducto.css">
    <script src="../js/libreria.js" type="text/javascript"></script>
</head>
<body>
<?php include("../administrador/capas/barra_navegacion.php"); ?>
<center>
<h2>Agregar Producto</h2>

<form action="producto_crud.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="codigo" placeholder="Codigo" required><br><br>
    <input type="text" name="nombre" placeholder="Nombre" required><br><br>
    <textarea name="descripcion" placeholder="Descripción" required></textarea><br><br> 
    <input type="number" name="precio_compra" step="0.01" placeholder="Precio de Compra" required><br><br>
    <input type="number" name="precio_venta" step="0.01" placeholder="Precio de Venta" required><br><br>
    <input type="number" name="stock_actual" placeholder="Stock" required><br><br>
    <input type="file" name="imagen" accept="image/*" required><br><br>
    <input type="hidden" name="accion" value="agregar">
    <button type="submit">Agregar</button><br><br><br>
    
    <a href="../administrador/producto_vista.php" class="btn-volver">🔙 Volver</a>
    
</form>
</center>
</body>
</html>
