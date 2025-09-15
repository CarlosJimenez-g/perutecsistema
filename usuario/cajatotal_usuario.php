<?php
include("../backend/conexion.php");
$conn = conexion();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = $_POST['fecha'];
    $trabajador = $_POST['trabajador'];
    $turno = $_POST['turno'];
    $monto_base = floatval($_POST['monto_base']);
    $monto_digital = floatval($_POST['monto_digital']);
    $observaciones = floatval($_POST['observaciones']);
    $justificacion_observacion = $_POST['justificacion_observacion'];

    $total_caja = $monto_base + $monto_digital + $observaciones;

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
    <title>PERUTEC</title>
    <link rel="icon" type="image/png" href="../imagenes/logo_perutec.png">
    <link rel="stylesheet" href="../css/estilocajatotal.css">
    <link rel="stylesheet" href="../css/barra_navegacion.css">
    <script>
        function confirmarEnvio(event) {
            event.preventDefault(); // Detiene el envío del formulario

            let fecha = document.querySelector("[name='fecha']").value;
            let trabajador = document.querySelector("[name='trabajador']").value;
            let turno = document.querySelector("[name='turno']").value;
            let monto_base = document.querySelector("[name='monto_base']").value;
            let monto_digital = document.querySelector("[name='monto_digital']").value;
            let observaciones = document.querySelector("[name='observaciones']").value;
            let justificacion = document.querySelector("[name='justificacion_observacion']").value;

            let total = (parseFloat(monto_base) || 0) + (parseFloat(monto_digital) || 0) + (parseFloat(observaciones) || 0);

            let mensaje = "CONFIRMACIÓN DE CUADRE DE CAJA\n\n";
            mensaje += "Fecha: " + fecha + "\n";
            mensaje += "Trabajador: " + trabajador + "\n";
            mensaje += "Turno: " + turno + "\n";
            mensaje += "Monto base: S/ " + monto_base + "\n";
            mensaje += "Monto digital: S/ " + monto_digital + "\n";
            mensaje += "Observaciones: S/ " + observaciones + "\n";
            mensaje += "Justificación: " + justificacion + "\n";
            mensaje += "--------------------------\n";
            mensaje += "TOTAL CAJA: S/ " + total.toFixed(2) + "\n\n";
            mensaje += "¿Desea confirmar el guardado?";

            if (confirm(mensaje)) {
                event.target.submit(); // Envía el formulario si confirma
            }
        }
    </script>
</head>
<body>
    <center>
        <?php include("capas_usuario/barra_navegacion_usuario.php"); ?>
        <h1>Cierre de Caja Diario</h1>
        <?php if (isset($mensaje)) echo "<p><strong>$mensaje</strong></p>"; ?>

        <form method="POST" onsubmit="confirmarEnvio(event)">
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" required><br><br>

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
            
            <label for="observaciones">Observaciones (S/):</label>
            <input type="number" name="observaciones" step="0.01" required><br><br>

            <label for="justificacion_observacion">Justifica la observación:</label>
            <input type="text" name="justificacion_observacion" required><br><br>

            <button type="submit">Guardar Cierre de Caja</button>
        </form>
    </center>
</body>
</html>
