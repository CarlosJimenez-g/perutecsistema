<?php
$id_venta = isset($_GET['id']) ? intval($_GET['id']) : 0;
$ruta_pdf = "../pdf_ventas/venta_" . $id_venta . ".pdf";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Venta Registrada</title>
    <style>
        body {
            background: #f2f2f2;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .contenedor {
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }

        .icono-exito {
            font-size: 60px;
            color: #28a745;
        }

        h2 {
            margin: 20px 0 10px;
            color: #333;
        }

        p {
            color: #555;
            margin-bottom: 30px;
        }

        .botones a {
            display: inline-block;
            margin: 10px 10px 0;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .btn-ver {
            background: #007bff;
            color: white;
        }

        .btn-ver:hover {
            background: #0056b3;
        }

        .btn-volver {
            background: #6c757d;
            color: white;
        }

        .btn-volver:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <div class="icono-exito">✅</div>
        <h2>¡Venta registrada con éxito!</h2>
        <p>El comprobante ha sido generado y puedes verlo o regresar al listado de ventas.</p>

        <div class="botones">
            <?php if (file_exists($ruta_pdf)): ?>
                <a href="<?= $ruta_pdf ?>" target="_blank" class="btn-ver">📄 Ver Comprobante</a>
            <?php else: ?>
                <p style="color:red;">⚠️ No se encontró el comprobante PDF.</p>
            <?php endif; ?>

            <a href="ventas_hechas.php" class="btn-volver">🔙 Volver a Ventas</a>
        </div>
    </div>
</body>
</html>
