<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    
    session_start();

    require_once('../conexion.php');

    $idHab = $_GET["id"];
    $sql = "SELECT numero FROM habitaciones WHERE id_habitacion = ?";
    $stmt = $conexion->prepare($sql);

    $stmt->bind_param("i", $idHab);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if($fila = $resultado->fetch_assoc()){
        echo json_encode($fila);
    }
    else{
        echo json_encode(['exito' => false, 'mensaje' => 'Habitación no encontrada']);
    }

    $stmt->close();
    $conexion->close();

?>