<?php
    // Headers para comunicación con el frontend Vue
    header('Access-Control-Allow-Origin: *');        // Permite peticiones desde localhost:5173 (CORS)
    header('Content-Type: application/json');         // Respuesta en formato JSON

    // Conexión a la base de datos
    require_once('../conexion.php');

    // Recibe los datos del formulario enviados por POST desde ModalReservaComponent.vue
    // trim() elimina espacios en blanco al inicio y final de los strings
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

    // Verificación de disponibilidad: busca si ya existe una reserva activa para esa habitación
    // en las fechas solicitadas. La lógica de solapamiento es:
    // fecha_entrada de la existente < fecha_salida nueva AND fecha_salida de la existente > fecha_entrada nueva
    // Solo chequea reservas con estado 'pendiente' o 'confirmada' (las canceladas/finalizadas no bloquean)
    $sqlCheck = "SELECT id_reserva FROM reservas WHERE id_habitacion = ? AND estado IN ('pendiente','confirmada') AND fecha_entrada < ? AND fecha_salida > ?";
    $stmtCheck = $conexion->prepare($sqlCheck);

    // "iss" = integer (id_habitacion), string (fecha_salida), string (fecha_entrada)
    $stmtCheck->bind_param("iss", $id_habitacion, $fecha_salida, $fecha_entrada);
    $stmtCheck->execute();
    
    // store_result() guarda el resultado en memoria para poder usar num_rows
    $stmtCheck->store_result();

    // Si num_rows == 0, no hay reservas que se pisen → se puede insertar
    if ($stmtCheck->num_rows == 0) {

        // INSERT con prepared statement para evitar inyección SQL
        // 15 campos: los ? se reemplazan con bind_param de forma segura
        $sql = "INSERT INTO reservas (id_habitacion, id_usuario, dni, nombre, apellido, telefono, email, domicilio, provincia, pais, fecha_entrada, fecha_salida, observacion, total, estado) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $conexion->prepare($sql);
        $estado = 'pendiente';      // Las reservas nuevas siempre arrancan como pendientes
        $id_usuario = 1;            // ID del admin que carga la reserva (hardcodeado por ahora)

        // "iisssssssssssis" define el tipo de cada parámetro: i=integer, s=string
        $stmt->bind_param("iisssssssssssis", $id_habitacion, $id_usuario, $dni, $nombre, $apellido, $telefono, $email, $domicilio, $provincia, $pais, $fecha_entrada, $fecha_salida, $observacion, $total, $estado);

        if ($stmt->execute()) {
            echo json_encode(['exito' => true, 'mensaje' => 'Reserva registrada correctamente']);
        } else {

            // $stmt->error devuelve el mensaje de error de MySQL si falla la inserción
            echo json_encode(['exito' => false, 'mensaje' => 'Error al registrar: ' . $stmt->error]);
        }
        
        // Cierra el statement del INSERT (solo existe si entró al if)
        $stmt->close();
    }
    else{

        // Si num_rows > 0, ya hay una reserva activa en esas fechas → no se puede reservar
        echo json_encode(['exito' => false, 'mensaje' => 'La habitación no está disponible en esas fechas']);
    }

    // Libera recursos del check y cierra la conexión
    $stmtCheck->close();
    $conexion->close();

?>