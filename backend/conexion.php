<?php
function conexion(){
    $conn = new mysqli('localhost', 'root', '', 'perutec_bd');
    if ($conn->connect_error) {
        die('Error de conexión: ' . $conn->connect_error);
    }
    return $conn;
}
?>
