<?php
include("../backend/conexion.php");
$conn = conexion();

$codigo = $_GET['codigo'];
$resultado = $conn->query("SELECT * FROM producto WHERE codigo=$codigo");
$producto = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Producto</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link href="../css/barra_navegacion.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../css/tablaproducto.css">
    <script src="../js/libreria.js" type="text/javascript"></script>
</head>
<body>
<?php include("capas/barra_navegacion.php"); ?>
<center>
<h2>Actualizar Producto</h2>

<form action="../backend/producto_crud.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="codigo" value="<?= $producto['codigo'] ?>">

    <label>Nombre:</label><br>
    <input type="text" name="nombre" value="<?= $producto['nombre'] ?>" required><br><br>

    <label>Descripción:</label><br>
    <textarea name="descripcion" required><?= $producto['descripcion'] ?></textarea><br><br>

    <label>Precio Compra:</label><br>
    <input type="number" name="precio_compra" value="<?= $producto['precio_compra'] ?>" step="0.01" required><br><br>

    <label>Precio Venta:</label><br>
    <input type="number" name="precio_venta" value="<?= $producto['precio_venta'] ?>" step="0.01" required><br><br>

    <label>Stock:</label><br>
    <input type="number" name="stock_actual" value="<?= $producto['stock_actual'] ?>" required><br><br>

    <label>Imagen:</label><br>
    <input type="file" name="imagen" accept="image/*"><br><br>

    <label>Fecha de Actualización:</label><br>
    <input type="date" name="ultima_actualizacion" value="<?= $producto['ultima_actualizacion'] ?>"><br><br>

    <input type="hidden" name="accion" value="actualizar">
    <button type="submit">Actualizar</button>
</form>
</center>
</body>
</html>
