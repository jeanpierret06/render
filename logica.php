<?php

date_default_timezone_set('America/Bogota');
$hoy = date("Y-m-d H:i:s");  

require 'vendor/autoload.php'; // Cargar Composer

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Conexión a MongoDB Atlas
        $cliente = new MongoDB\Client("mongodb+srv://jeanpierret06:06092006@cluster0.ymxjlft.mongodb.net/?appName=Cluster0");
        
        $db = $cliente->form;
        $coleccion = $db->people; // Nombre de la colección    
        
        // Inserción de datos
        $resultado = $coleccion->insertOne([
            "apellidos" => $_POST["apellidos"],
            "nombres"   => $_POST["nombres"],
            "color"     => $_POST["color"],
            "comida"    => $_POST["comida"],
            "pelicula"  => $_POST["pelicula"],
            "registro"  => $hoy
        ]);

        $idInsertado = $resultado->getInsertedId();

        // RENDERIZADO VISUAL "TRY HARD" DE ÉXITO
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="refresh" content="3;url=index.html">
            <title>Registro Exitoso</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
            <style>
                body {
                    background: radial-gradient(circle at center, #1a0b2e 0%, #05010d 100%);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-family: 'Segoe UI', sans-serif;
                    color: #fff;
                    overflow: hidden;
                }
                .success-container {
                    background: rgba(10, 5, 20, 0.9);
                    border: 2px solid #00f0ff;
                    box-shadow: 0 0 35px rgba(0, 240, 255, 0.4);
                    border-radius: 15px;
                    padding: 40px;
                    max-width: 600px;
                    width: 90%;
                    text-align: center;
                }
                .glitch-text {
                    color: #00f0ff;
                    text-shadow: 0 0 10px rgba(0, 240, 255, 0.6);
                    font-weight: 800;
                    letter-spacing: 2px;
                }
                .id-badge {
                    background: rgba(255, 0, 85, 0.1);
                    border: 1px dashed #ff0055;
                    color: #ff0055;
                    font-family: 'Courier New', Courier, monospace;
                    padding: 10px;
                    border-radius: 5px;
                    word-break: break-all;
                    margin-top: 15px;
                }
                .loader {
                    height: 3px;
                    width: 100%;
                    background: rgba(255,255,255,0.1);
                    margin-top: 30px;
                    position: relative;
                    overflow: hidden;
                }
                .loader::after {
                    content: '';
                    position: absolute;
                    left: 0; top: 0; height: 100%; width: 0;
                    background: #ff0055;
                    animation: loadProgress 3s linear forwards;
                }
                @keyframes loadProgress {
                    to { width: 100%; }
                }
            </style>
        </head>
        <body>
            <div class="success-container animate__animated animate__backInDown">
                <h2 class="glitch-text mb-4">⚡ TRANSACCIÓN COMPLETADA ⚡</h2>
                <p class="text-white-50">El documento ha sido indexado con éxito en el clúster de MongoDB Atlas.</p>
                
                <div class="id-badge">
                    ID_DOC >> <?php echo $idInsertado; ?>
                </div>

                <p class="text-muted mt-4 small">Reinicializando interfaz del sistema en 3 segundos...</p>
                <div class="loader"></div>
            </div>
        </body>
        </html>
        <?php

    } catch (Exception $e) {
        // En caso de que falle la conexión a Atlas
        echo "<body style='background:#05010d; color:#ff0055; padding:50px; font-family:sans-serif;'>";
        echo "<h3>[ERROR CRÍTICO DE SISTEMA]: No se pudo conectar con la base de datos.</h3>";
        echo "<p>" . $e->getMessage() . "</p>";
        echo "<a href='index.html' style='color:#00f0ff;'>Volver a intentar</a>";
        echo "</body>";
    }
} else {
    // Si entran de forma directa sin pasar por el formulario
    header("Location: index.html");
    exit();
}
?>