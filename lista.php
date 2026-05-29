<?php
require 'vendor/autoload.php'; // Cargar Composer

try {
    // Conexión a MongoDB Atlas
    $cliente = new MongoDB\Client("mongodb+srv://jeanpierret06:06092006@cluster0.ymxjlft.mongodb.net/?appName=Cluster0");
    $db = $cliente->form;
    $coleccion = $db->people;

    // Obtener todos los documentos ordenados por fecha de registro (más recientes primero)
    $registrados = $coleccion->find([], ['sort' => ['registro' => -1]]);
} catch (Exception $e) {
    die("[ERROR CRÍTICO]: No se pudo conectar a la base de datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Base de Datos - Aprendices</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        body {
            background: radial-gradient(circle at center, #1a0b2e 0%, #05010d 100%);
            min-height: 100vh;
            font-family: 'Orbitron', 'Segoe UI', sans-serif;
            color: #fff;
            padding: 40px 20px;
            position: relative;
        }

        /* Líneas de escaneo de fondo */
        body::before {
            content: " ";
            display: block;
            position: absolute;
            top: 0; left: 0; bottom: 0; right: 0;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%), linear-gradient(90deg, rgba(255, 0, 0, 0.06), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.06));
            z-index: 1;
            background-size: 100% 4px, 6px 100%;
            pointer-events: none;
        }

        .table-container {
            background: rgba(10, 5, 20, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 2px solid #00f0ff;
            border-radius: 15px;
            padding: 30px;
            z-index: 2;
            position: relative;
            box-shadow: 0 0 30px rgba(0, 240, 255, 0.2);
        }

        .glitch-title {
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 3px;
            text-shadow: 2px 2px #ff0055, -2px -2px #00f0ff;
            color: #fff;
        }

        /* Tabla Cyberpunk Custom */
        .table-cyber {
            color: #fff !important;
            border-color: rgba(0, 240, 255, 0.2) !important;
        }

        .table-cyber thead th {
            background-color: rgba(255, 0, 85, 0.15) !important;
            color: #ff0055 !important;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            border-bottom: 2px solid #ff0055 !important;
        }

        .table-cyber tbody tr {
            background-color: rgba(255, 255, 255, 0.02) !important;
            transition: all 0.2s ease;
        }

        .table-cyber tbody tr:hover {
            background-color: rgba(0, 240, 255, 0.08) !important;
            box-shadow: inset 0 0 10px rgba(0, 240, 255, 0.2);
        }

        .btn-back {
            background: transparent;
            border: 2px solid #00f0ff;
            color: #00f0ff;
            padding: 10px 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 6px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-back:hover {
            background: #00f0ff;
            color: #05010d;
            box-shadow: 0 0 20px #00f0ff;
        }
    </style>
</head>
<body>

    <div class="container table-container animate__animated animate__fadeIn">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <h2 class="glitch-title m-0">⚡ REGISTROS EN LA RED ⚡</h2>
            <a href="index.html" class="btn btn-back">« Volver al Panel</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-cyber align-middle m-0">
                <thead>
                    <tr>
                        <th>Apellidos</th>
                        <th>Nombres</th>
                        <th>Color Favorito</th>
                        <th>Comida Favorito</th>
                        <th>Cine / Literatura</th>
                        <th>Fecha de Registro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $contador = 0;
                    foreach ($registrados as $doc): 
                        $contador++;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($doc['apellidos'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($doc['nombres'] ?? ''); ?></td>
                            <td>
                                <span style="color: #00f0ff; font-weight: 600;">
                                    <?php echo htmlspecialchars($doc['color'] ?? ''); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($doc['comida'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($doc['pelicula'] ?? ''); ?></td>
                            <td class="text-white-50 small"><?php echo htmlspecialchars($doc['registro'] ?? ''); ?></td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if ($contador === 0): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                [ SYSTEM ERROR: No se encontraron registros indexados en la base de datos ]
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>