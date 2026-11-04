<?php

    session_start();
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    require_once('../conexion.php');

    $id = $_POST["id"];
    $sql = "DELETE FROM categorias_habitacion WHERE id_categoria = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);

    if($stmt->execute() && $stmt->affected_rows > 0){
        echo json_encode(['exito' => true, 'mensaje' => 'Categoria eliminada correctamente']);
    }
    else{
        echo json_encode(['exito' => false, 'mensaje' => 'No se pudo eliminar. Puede tener habitaciones asociadas.']);
    }

    $stmt->close();
    $conexion->close();

?>