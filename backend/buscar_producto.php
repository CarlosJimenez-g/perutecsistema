<?php
include('conexion.php');
$conn = conexion();

if (isset($_POST['query'])) {
  $query = $conn->real_escape_string($_POST['query']);
  $sql = "SELECT * FROM producto WHERE codigo LIKE '%$query%' OR nombre LIKE '%$query%' LIMIT 5";
  $result = $conn->query($sql);

  while ($row = $result->fetch_assoc()) {
    echo "<div style='cursor:pointer; padding:5px; border-bottom:1px solid #ccc;' onclick=\"agregarAlCarrito('{$row['codigo']}', '{$row['nombre']}', {$row['precio']}, {$row['stock_actual']})\">
    <strong>{$row['nombre']}</strong> (Código: {$row['codigo']}) - S/ {$row['precio']} - Stock: {$row['stock_actual']}
    </div>";
  }
}
?>
