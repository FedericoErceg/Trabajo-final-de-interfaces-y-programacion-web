<?php

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    require_once('../conexion.php');

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

    $sqlCheck = "SELECT id_reserva FROM reservas WHERE id_habitacion = ? AND estado IN ('pendiente','confirmada') AND fecha_entrada < ? AND fecha_salida > ?";
    $stmtCheck = $conexion->prepare($sqlCheck);
    $stmtCheck->bind_param("iss", $id_habitacion, $fecha_salida, $fecha_entrada);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows == 0) {
        $sql = "INSERT INTO reservas (id_habitacion, id_usuario, dni, nombre, apellido, telefono, email, domicilio, provincia, pais, fecha_entrada, fecha_salida, observacion, total, estado) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $conexion->prepare($sql);
        $estado = 'pendiente';
        $id_usuario = 1;
        $stmt->bind_param("iisssssssssssis", $id_habitacion, $id_usuario, $dni, $nombre, $apellido, $telefono, $email, $domicilio, $provincia, $pais, $fecha_entrada, $fecha_salida, $observacion, $total, $estado);

        if ($stmt->execute()) {
            echo json_encode(['exito' => true, 'mensaje' => 'Reserva registrada correctamente']);
        } else {
            echo json_encode(['exito' => false, 'mensaje' => 'Error al registrar: ' . $stmt->error]);
        }
        
        $stmt->close();
    }
    else{
        echo json_encode(['exito' => false, 'mensaje' => 'La habitación no está disponible en esas fechas']);
    }

    $stmtCheck->close();
    $conexion->close();

?>