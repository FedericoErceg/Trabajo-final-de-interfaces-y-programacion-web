<?php
    // Datos de conexión al servidor MySQL (XAMPP usa estos valores por defecto)
    $server = "localhost";
    $user = "root";
    $password = "";
    $dataBase = "hotel_reservas";

    // Crea una nueva conexión a MySQL usando la clase mysqli.
    $conexion = new mysqli($server, $user, $password, $dataBase);

    // Verifica si la conexión falló
    // connect_error devuelve null si la conexión fue exitosa, o un mensaje de error si falló
    // die() detiene la ejecución del script y muestra el mensaje de error
    if($conexion->connect_error){
        die ("Error de coneccion: " . $conexion->connect_error);
    }

    // Este archivo se incluye con require_once() en todos los archivos del backend
    // La variable $conexion queda disponible en el archivo que lo importa
?>