<?php

date_default_timezone_set('America/Bogota');
$hoy = date("Y-m-d H:i:s");  

require 'vendor/autoload.php'; // Cargar Composer

    $cliente = new MongoDB\Client("mongodb+srv://jeanpierret06:<jeanpierret06>@cluster0.ymxjlft.mongodb.net/?appName=Cluster0");
    $db = $cliente->form;
    $coleccion = $db->people;	//Nombre de la coleccion	
    $resultado = $coleccion->insertOne([
        "apellidos" => $_POST["apellidos"],
        "nombres" => $_POST["nombres"],
        "color" => $_POST["color"],
        "comida" => $_POST["comida"],
        "pelicula" => $_POST["pelicula"],
        "registro" => $hoy
    ]);
    echo "<center><h3 style='border:1px solid green;background-color:rgb(64,145,108);color:white;padding:1%;'>Documento insertado con ID: " . $resultado->getInsertedId() . "</h3></center>";
    
include "index.html";

?>
