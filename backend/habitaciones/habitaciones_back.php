<?php
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    
    require_once('../conexion.php');

    $sql = "SELECT h.*, c.tipo, c.capacidad, c.precio_noche 
            FROM habitaciones h 
            INNER JOIN categorias_habitacion c 
            ON h.id_categoria_habitacion = c.id_categoria";

    $resultado = $conexion->query($sql);
    $habitaciones = [];

    while ($fila = $resultado->fetch_assoc()) {
        $habitaciones[] = $fila;
    }

    echo json_encode($habitaciones);
    $conexion->close();
?>