<?php

    session_start();
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');


    require_once('../conexion.php');

    $idCat = $_GET["id"];
    $sql = "SELECT * FROM categorias_habitacion WHERE id_categoria = ?";
    $stmt = $conexion->prepare($sql);

    $stmt->bind_param("i", $idCat);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if($fila = $resultado->fetch_assoc()){
        echo json_encode($fila);
    }
    else{
        echo json_encode(['exito' => false, 'mensaje' => 'Categoria no encontrada']);
    }

    $stmt->close();
    $conexion->close();

?>