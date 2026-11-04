<?php

    $server = "localhost";
    $user = "root";
    $password = "";
    $dataBase = "hotel_reservas";

    $conexion = new mysqli($server, $user, $password, $dataBase);

    if($conexion->connect_error){
        die ("Error de coneccion: " . $conexion->connect_error);
    }
    

?>  