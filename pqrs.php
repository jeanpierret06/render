<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mensaje_feedback = "";
$status_feedback = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Coloca tu cadena de conexión real de Atlas aquí
    $mongo_uri = "mongodb+srv://jeanpierret06:06092006@cluster0.ymxjlft.mongodb.net/?appName=Cluster0";
    
    try {
        $manager = new MongoDB\Driver\Manager($mongo_uri);
        
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS);
        $detalle = filter_input(INPUT_POST, 'detalle', FILTER_SANITIZE_SPECIAL_CHARS);
        
        $documento = [
            '_id' => new MongoDB\BSON\ObjectId(),
            'nombre' => $nombre,
            'email' => $email,
            'tipo' => $tipo,
            'detalle' => $detalle,
            'fecha_radicacion' => new MongoDB\BSON\UTCDateTime(new DateTime())
        ];
        
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->insert($documento);
        
        // 2. CAMBIA "EL_NOMBRE_DE_TU_BD_EXISTENTE" por el nombre real de tu base de datos
        $manager->executeBulkWrite('form.pqrs', $bulk);
        
        $status_feedback = "success";
        $mensaje_feedback = "⚡ [ RADICACIÓN EXITOSA ] -> TICKET ENVIADO AL CLUSTER ATLAS";
        
    } catch (MongoDB\Driver\Exception\Exception $e) {
        $status_feedback = "error";
        $mensaje_feedback = "❌ [ ERROR CRÍTICO DE ENLACE ] -> " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Radicación PQRS - Sistema Premium</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        /* Base de Pantalla Cyberpunk */
        body {
            background-color: #030108;
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Orbitron', 'Segoe UI', sans-serif;
            color: #fff;
            padding: 20px;
            position: relative;
            margin: 0;
        }

        #cyber-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 0;
            pointer-events: none;
        }

        body::before {
            content: " ";
            display: block;
            position: absolute;
            top: 0; left: 0; bottom: 0; right: 0;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%), linear-gradient(90deg, rgba(255, 0, 0, 0.04), rgba(0, 255, 0, 0.01), rgba(0, 0, 255, 0.04));
            z-index: 1;
            background-size: 100% 4px, 6px 100%;
            pointer-events: none;
        }

        /* Contenedor de Formulario */
        .form-container {
            background: rgba(8, 4, 18, 0.85);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 2px solid #ffaa00; /* Borde ámbar/naranja para sección de soporte */
            border-radius: 15px;
            padding: 40px;
            width: 100%;
            max-width: 550px;
            z-index: 2;
            position: relative;
            box-shadow: 0 0 30px rgba(255, 170, 0, 0.15), inset 0 0 15px rgba(255, 170, 0, 0.05);
            animation: ultraEntrance 1.2s cubic-bezier(0.25, 1, 0.5, 1) forwards;
        }

        @keyframes ultraEntrance {
            0% { transform: scale(0.3) rotate(15deg) translateY(500px); opacity: 0; filter: blur(20px) brightness(3); }
            100% { transform: scale(1) rotate(0deg) translateY(0); opacity: 1; }
        }

        .glitch-title {
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #fff;
            text-shadow: 2px 2px #ffaa00, -2px -2px #00f0ff;
            margin-bottom: 35px;
        }

        .form-label {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            color: #ffaa00;
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 170, 0, 0.3);
            color: #fff;
            border-radius: 6px;
            padding: 12px;
            transition: all 0.25s ease;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(255, 170, 0, 0.08);
            border-color: #ff0055;
            color: #fff;
            box-shadow: 0 0 15px rgba(255, 0, 85, 0.4);
            transform: scale(1.02) translateX(5px);
        }

        .form-select option {
            background: #0a0514;
            color: #fff;
        }

        /* Botón de Envíos Metálico Naranja */
        .btn-submit-pqrs {
            background: transparent;
            border: 2px solid #ffaa00;
            color: #ffaa00;
            padding: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-radius: 6px;
            width: 100%;
            margin-top: 25px;
            transition: all 0.3s ease;
        }

        .btn-submit-pqrs:hover {
            background: #ffaa00;
            color: #05010d;
            box-shadow: 0 0 35px #ffaa00;
            transform: translateY(-3px);
        }

        .btn-back {
            display: block;
            text-align: center;
            text-decoration: none;
            background: rgba(0, 240, 255, 0.05);
            border: 2px solid #00f0ff;
            color: #00f0ff;
            padding: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-radius: 6px;
            width: 100%;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #00f0ff;
            color: #05010d;
            box-shadow: 0 0 30px #00f0ff;
        }

        /* Alertas de Feedback */
        .cyber-alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 25px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            text-align: center;
        }
        .alert-success-cyber {
            background: rgba(0, 255, 136, 0.1);
            border: 1px solid #00ff88;
            color: #00ff88;
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.2);
        }
        .alert-error-cyber {
            background: rgba(255, 0, 85, 0.1);
            border: 1px solid #ff0055;
            color: #ff0055;
            box-shadow: 0 0 15px rgba(255, 0, 85, 0.2);
        }

        .tech-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, rgba(255, 170, 0, 0.4) 50%, transparent 100%);
            margin-top: 25px;
        }
    </style>
</head>

<body>

    <canvas id="cyber-canvas"></canvas>

    <div class="form-container animate__animated animate__fadeIn">

        <header class="text-center">
            <h2 class="glitch-title">CENTRAL PQRS</h2>
        </header>

        <?php if (!empty($mensaje_feedback)): ?>
            <div class="cyber-alert <?php echo $status_feedback == 'success' ? 'alert-success-cyber' : 'alert-error-cyber'; ?>">
                <?php echo $mensaje_feedback; ?>
            </div>
        <?php endif; ?>

        <form action="pqrs.php" method="post">

            <div class="mb-3">
                <label for="inputNombre" class="form-label">Nombre Completo</label>
                <input type="text" required maxlength="150" name="nombre" class="form-control" id="inputNombre"
                    placeholder=">> IDENTIFICACIÓN DE OPERARIO">
            </div>

            <div class="mb-3">
                <label for="inputEmail" class="form-label">Correo Electrónico</label>
                <input type="email" required maxlength="150" name="email" class="form-control" id="inputEmail"
                    placeholder=">> DIRECCIÓN DE ENLACE DE RED">
            </div>

            <div class="mb-3">
                <label for="selectTipo" class="form-label">Tipo de Solicitud</label>
                <select required name="tipo" class="form-select" id="selectTipo">
                    <option value="" disabled selected>>> SELECCIONA UNA CATEGORÍA</option>
                    <option value="Peticion">PETICIÓN</option>
                    <option value="Queja">QUEJA</option>
                    <option value="Reclamo">RECLAMO</option>
                    <option value="Sugerencia">SUGERENCIA</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="textareaDetalle" class="form-label">Detalles de la Solicitud</label>
                <textarea required maxlength="1000" name="detalle" class="form-control" id="textareaDetalle" rows="4"
                    placeholder=">> DESCRIBE LA ANOMALÍA O REQUERIMIENTO..."></textarea>
            </div>

            <button type="submit" class="btn btn-submit-pqrs">
                [ TRANSMITIR SOLICITUD ]
            </button>
        </form>

        <div class="tech-divider"></div>

        <a href="index.html" class="btn btn-back">
            ◀ [ VOLVER AL PANEL GENERAL ]
        </a>

    </div>

    <script>
        const canvas = document.getElementById('cyber-canvas');
        const ctx = canvas.getContext('2d');

        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        const columnCount = Math.floor(canvas.width / 20);
        const streams = [];

        for (let i = 0; i < columnCount; i++) {
            streams.push({
                x: i * 25 + Math.random() * 15,
                y: Math.random() * canvas.height,
                speed: 1.5 + Math.random() * 3,
                length: 40 + Math.random() * 120,
                color: Math.random() > 0.5 ? '#ffaa00' : '#00f0ff' // Cambiado a Ámbar y Cian
            });
        }

        function drawCyberBackground() {
            ctx.fillStyle = 'rgba(3, 1, 8, 0.08)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            for (let i = 0; i < streams.length; i++) {
                const stream = streams[i];
                ctx.beginPath();
                let gradient = ctx.createLinearGradient(stream.x, stream.y, stream.x, stream.y + stream.length);
                gradient.addColorStop(0, 'transparent');
                gradient.addColorStop(1, stream.color);
                
                ctx.strokeStyle = gradient;
                ctx.lineWidth = 1.5;
                ctx.moveTo(stream.x, stream.y);
                ctx.lineTo(stream.x, stream.y + stream.length);
                ctx.stroke();

                stream.y += stream.speed;

                if (stream.y > canvas.height) {
                    stream.y = -stream.length;
                    stream.x = Math.random() * canvas.width;
                }
            }
            requestAnimationFrame(drawCyberBackground);
        }
        drawCyberBackground();
    </script>
</body>

</html>
