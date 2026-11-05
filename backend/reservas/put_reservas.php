<?php

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    require_once('../conexion.php');

    $id = $_POST["id"];
    $id_habitacion = $_POST["id_habitacion"];
    $dni = trim($_POST["dni"]);
    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $telefono = trim($_POST["telefono"]);
    $email = trim($_POST["email"]);
    $domicilio = trim($_POST["domicilio"]);
    $provincia = trim($_POST["provincia"]);
    $pais = trim($_POST["pais"]);
    $fecha_entrada = $_POST["fecha_entrada"];
    $fecha_salida = $_POST["fecha_salida"];
    $observacion = trim($_POST["observacion"]);
    $total = $_POST["total"];

    $sqlCheck = "SELECT id_reserva FROM reservas 
    WHERE id_habitacion = ? AND estado IN ('pendiente','confirmada') 
    AND fecha_entrada < ? AND fecha_salida > ? AND id_reserva != ?";

    $stmtCheck = $conexion->prepare($sqlCheck);
    $stmtCheck->bind_param("issi", $id_habitacion, $fecha_salida, $fecha_entrada, $id);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if($stmtCheck->num_rows == 0){
        
        $sql = "UPDATE reservas 
        SET id_habitacion = ?, 
        dni = ?, 
        nombre = ?, 
        apellido = ?, 
        telefono = ?, 
        email = ?, 
        domicilio = ?, 
        provincia = ?, 
        pais = ?, 
        fecha_entrada = ?, 
        fecha_salida = ?, 
        observacion = ?, 
        total = ? 
        WHERE id_reserva = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("isssssssssssii", $id_habitacion, $dni, $nombre, $apellido, $telefono, $email, $domicilio, $provincia, $pais, $fecha_entrada, $fecha_salida, $observacion, $total, $id);

        if ($stmt->execute()) {
            echo json_encode(['exito' => true, 'mensaje' => 'Reserva modificada correctamente']);
        } else {
            echo json_encode(['exito' => false, 'mensaje' => 'Error al modificar: ' . $stmt->error]);
        }
        
        $stmt->close();
    }
    else{
        echo json_encode(['exito' => false, 'mensaje' => 'La habitación no está disponible en esas fechas']);
    }

    $stmtCheck->close();
    $conexion->close();

?>