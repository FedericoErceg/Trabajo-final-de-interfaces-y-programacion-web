<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    require_once('../conexion.php');

    $sql = "SELECT r.*, h.numero 
            FROM reservas r 
            INNER JOIN habitaciones h ON r.id_habitacion = h.id_habitacion
            ORDER BY r.fecha_creacion DESC";
    
    $resultado = $conexion->query($sql);
    $reservas = [];

    while($fila = $resultado->fetch_assoc()){
        $reservas[] = $fila;
    }

    echo json_encode($reservas);
    $conexion->close();

?>