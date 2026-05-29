<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mensaje_feedback = "";
$status_feedback = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Coloca tu cadena de conexión real de Atlas aquí
    $mongo_uri = "mongodb+srv://TU_USUARIO:TU_PASSWORD@TU_CLUSTER.mongodb.net/?retryWrites=true&w=majority";
    
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
        $manager->executeBulkWrite('EL_NOMBRE_DE_TU_BD_EXISTENTE.pqrs', $bulk);
        
        $status_feedback = "success";
        $mensaje_feedback = "⚡ [ RADICACIÓN EXITOSA ] -> TICKET ENVIADO AL CLUSTER ATLAS";
        
    } catch (MongoDB\Driver\Exception\Exception $e) {
        $status_feedback = "error";
        $mensaje_feedback = "❌ [ ERROR CRÍTICO DE ENLACE ] -> " . $e->getMessage();
    }
}
?>
