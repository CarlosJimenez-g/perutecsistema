<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'conexion.php';
$conn = conexion();

$carpetaDestino = "../imagenes/";

function subirImagen($inputName, $carpetaDestino) {
    if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === 0) {
        $nombreArchivo = basename($_FILES[$inputName]["name"]);
        $rutaDestino = $carpetaDestino . $nombreArchivo;

        if (move_uploaded_file($_FILES[$inputName]["tmp_name"], $rutaDestino)) {
            return $rutaDestino;
        }
    }
    return null;
}

// AGREGAR PRODUCTO
if (isset($_POST['accion']) && $_POST['accion'] == "agregar") {
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio_compra = $_POST['precio_compra'];
    $precio_venta = $_POST['precio_venta'];
    $ganancia_t = $precio_venta - $precio_compra;
    $stock = $_POST['stock_actual'];
    $imagen = subirImagen("imagen", $carpetaDestino);
    $fecha_ingreso = date('Y-m-d'); 

    $sql = "INSERT INTO producto (codigo, nombre, descripcion, precio_compra, precio_venta, ganancia_t, stock_actual, imagen, fecha_ingreso)
            VALUES ('$codigo','$nombre', '$descripcion', '$precio_compra','$precio_venta', '$ganancia_t','$stock', '$imagen', '$fecha_ingreso')";
    $conn->query($sql);
    header("Location: ../administrador/producto_vista.php");
}

// ACTUALIZAR PRODUCTO
if (isset($_POST['accion']) && $_POST['accion'] == "actualizar") {
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio_compra = $_POST['precio_compra'];
    $precio_venta = $_POST['precio_venta'];
    $stock = $_POST['stock_actual'];
    $imagen = subirImagen("imagen", $carpetaDestino);
    $ultima_actualizacion = $_POST['ultima_actualizacion'] ?? null;

    if ($imagen) {
        $sql = "UPDATE producto SET 
                nombre='$nombre',
                descripcion='$descripcion',
                precio_compra='$precio_compra',
                precio_venta='$precio_venta',
                stock_actual='$stock',
                imagen='$imagen',
                ultima_actualizacion=" . ($ultima_actualizacion ? "'$ultima_actualizacion'" : "NULL") . "
                WHERE codigo='$codigo'";
    } else {
        $sql = "UPDATE producto SET 
                nombre='$nombre',
                descripcion='$descripcion',
                precio_compra='$precio_compra',
                precio_venta='$precio_venta',
                stock_actual='$stock',
                ultima_actualizacion=" . ($ultima_actualizacion ? "'$ultima_actualizacion'" : "NULL") . "
                WHERE codigo='$codigo'";
    }

    $conn->query($sql);
    header("Location: ../administrador/producto_vista.php");
}

// ELIMINAR PRODUCTO
if (isset($_POST['accion']) && $_POST['accion'] == "eliminar") {
    $codigo = $_POST['codigo'];
    try {
        $conn->query("DELETE FROM producto WHERE codigo=$codigo");
        header("Location: ../administrador/producto_vista.php");
    } catch (mysqli_sql_exception $e) {
        echo "<script>alert('No se puede eliminar este producto porque está relacionado a una venta.'); window.location.href='../administrador/producto_vista.php';</script>";
    }
}

// CONSULTAR PRODUCTOS CON FILTRO
$search = $_POST['search'] ?? '';
$stock_min = $_POST['stock_min'] ?? '';

// Escapar valores
$search = $conn->real_escape_string($search);

// Armar la consulta
$sql = "SELECT * FROM producto WHERE (nombre LIKE '%$search%' OR descripcion LIKE '%$search%')";

// Aplicar filtro de stock si se ingresó
if ($stock_min !== '') {
    $stock_min = (int)$stock_min;
    $sql .= " AND stock_actual <= $stock_min";
}

$productos = $conn->query($sql);
?>
