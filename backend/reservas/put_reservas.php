<?php
    // Headers para comunicación con el frontend Vue
    header('Access-Control-Allow-Origin: *');        // Permite peticiones desde localhost:5173 (CORS)
    header('Content-Type: application/json');         // Respuesta en formato JSON

    // Conexión a la base de datos
    require_once('../conexion.php');

    // Recibe los datos del formulario enviados por POST desde ModalReservaComponent.vue
    // $id es el id_reserva de la reserva que se está editando
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
    
    // El estado se puede cambiar al editar (pendiente → confirmada, cancelada, finalizada)
    $estado = $_POST["estado"];

    // Verificación de disponibilidad excluyendo la reserva actual (AND id_reserva != ?)
    // Sin esa exclusión, la propia reserva se detectaría como conflicto al editarla
    // Misma lógica de solapamiento que en post_reservas.php
    $sqlCheck = "SELECT id_reserva FROM reservas 
    WHERE id_habitacion = ? AND estado IN ('pendiente','confirmada') 
    AND fecha_entrada < ? AND fecha_salida > ? AND id_reserva != ?";

    $stmtCheck = $conexion->prepare($sqlCheck);

    // "issi" = integer, string, string, integer
    $stmtCheck->bind_param("issi", $id_habitacion, $fecha_salida, $fecha_entrada, $id);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    // Si no hay conflictos de fechas con otras reservas → se puede actualizar
    if($stmtCheck->num_rows == 0){
        
        // UPDATE con prepared statement, actualiza todos los campos de la reserva
        // Incluye el estado para poder cambiar de pendiente a confirmada, etc.
        $sql = "UPDATE reservas SET id_habitacion = ?, dni = ?, nombre = ?, apellido = ?, telefono = ?, email = ?, domicilio = ?, provincia = ?, pais = ?, fecha_entrada = ?, fecha_salida = ?, observacion = ?, total = ?, estado = ? WHERE id_reserva = ?";

        $stmt = $conexion->prepare($sql);
        // "isssssssssssisi" = 15 parámetros: i(id_hab), s(dni), s(nombre)... i(total), s(estado), i(id)
        $stmt->bind_param("isssssssssssisi", $id_habitacion, $dni, $nombre, $apellido, $telefono, $email, $domicilio, $provincia, $pais, $fecha_entrada, $fecha_salida, $observacion, $total, $estado, $id);

        if ($stmt->execute()) {
            echo json_encode(['exito' => true, 'mensaje' => 'Reserva modificada correctamente']);
        } else {
            echo json_encode(['exito' => false, 'mensaje' => 'Error al modificar: ' . $stmt->error]);
        }
        
        // Cierra el statement del UPDATE (solo existe si entró al if)
        $stmt->close();
    }
    else{

        // Hay otra reserva activa que se pisa con las fechas → no se puede modificar
        echo json_encode(['exito' => false, 'mensaje' => 'La habitación no está disponible en esas fechas']);
    }

    // Libera recursos y cierra la conexión
    $stmtCheck->close();
    $conexion->close();

?>