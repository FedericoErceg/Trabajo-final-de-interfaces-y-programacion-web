<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    require_once('../conexion.php');

    $id = $_POST["id"];

    $sql = "DELETE FROM reservas WHERE id_reserva = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo json_encode(['exito' => true, 'mensaje' => 'Reserva eliminada correctamente']);
    } else {
        echo json_encode(['exito' => false, 'mensaje' => 'No se pudo eliminar la reserva']);
    }

    $stmt->close();
    $conexion->close();
?>