<?php
include("../backend/conexion.php");
$conn = conexion();

// Si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = $_POST['fecha'];
    $trabajador = $_POST['trabajador'];
    $turno = $_POST['turno'];
    $monto_base = floatval(value: $_POST['monto_base']);
    $monto_digital = floatval(value: $_POST['monto_digital']);
    $observaciones = floatval(value: $_POST['observaciones']);
    $justificacion_observacion = $_POST['justificacion_observacion'];


    // Calcular total de caja
    $total_caja = $monto_base + $monto_digital + $observaciones;

    // Insertar en la tabla cierre_caja
    $sql_insert = "INSERT INTO cierre_caja (fecha, nombre_trabajador, turno, monto_base, monto_digital, observaciones, justificacion_observacion, total_caja)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("sssdddsd", $fecha, $trabajador, $turno, $monto_base, $monto_digital, $observaciones, $justificacion_observacion, $total_caja);

    if ($stmt->execute()) {
        $mensaje = "✅ Cierre de caja registrado correctamente.";
    } else {
        $mensaje = "❌ Error al guardar: " . $conn->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cierre de Caja</title>
    <link rel="stylesheet" href="../css/estilocajatotal.css">
    <link rel="stylesheet" href="../css/estilo_venta.css">
    <link rel="stylesheet" href="../css/barra_navegacion.css">
</head>
<body>
    <center>
        <?php include("capas/barra_navegacion.php"); ?>

        <h1>Cierre de Caja Diario</h1>

        <?php if (isset($mensaje)) echo "<p><strong>$mensaje</strong></p>"; ?>

        <form method="POST">
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" value="<?= htmlspecialchars($fecha_actual) ?>" required><br><br>

            <label for="trabajador">Nombre del trabajador:</label>
            <input type="text" name="trabajador" required><br><br>

            <label for="turno">Turno:</label>
            <select name="turno" required>
                <option value="">Seleccionar</option>
                <option value="Mañana">Mañana</option>
                <option value="Tarde">Tarde</option>
                <option value="Todo el día">Todo el día</option>
            </select><br><br>

            <label for="monto_base">Monto base (S/):</label>
            <input type="number" name="monto_base" step="0.01" required><br><br>

            <label for="monto_digital">Monto digital (Yape/Plin) (S/):</label>
            <input type="number" name="monto_digital" step="0.01" required><br><br>
            
            <label for="observaciones"> Observaciones (S/)</label>
            <input type="number" name="observaciones" step="0.01" required><br><br>

            <label for="justificacion_observacion">Justifica la observacion:</label>
            <input type="text" name="justificacion_observacion" required><br><br>

            <button type="submit">Guardar Cierre de Caja</button>
        </form>
    </center>
</body>
</html>
