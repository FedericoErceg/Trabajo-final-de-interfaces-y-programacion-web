<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    session_start();

    require_once('../conexion.php');

    $id = $_POST["id"];

    $sql = "DELETE FROM habitaciones WHERE id_habitacion = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);

    if($stmt->execute() && $stmt->affected_rows > 0){
        echo json_encode(['exito' => true, 'mensaje' => 'Habitación eliminada correctamente']);
    }
    else{
        echo json_encode(['exito' => false, 'mensaje' => 'No se pudo eliminar. Puede tener reservas asociadas.']);
    }

    $stmt->close();
    $conexion->close();

?>