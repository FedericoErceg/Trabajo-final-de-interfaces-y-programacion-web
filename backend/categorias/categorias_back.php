<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    require_once('../conexion.php');

    $sql = "SELECT * FROM categorias_habitacion";
    $resultado = $conexion->query($sql);
    $categorias = [];

    while ($fila = $resultado->fetch_assoc()) {
        $categorias[] = $fila;
    }

    echo json_encode($categorias);
    $conexion->close();
?>