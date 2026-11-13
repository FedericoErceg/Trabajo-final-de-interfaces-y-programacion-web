<?php
    // Headers necesarios para la comunicación con el frontend Vue
    header('Access-Control-Allow-Origin: *');        // Permite peticiones desde localhost:5173 (CORS)
    header('Content-Type: application/json');         // La respuesta será JSON

    // Incluye la conexión a la base de datos (sube un nivel porque estamos en backend/reservas/)
    require_once('../conexion.php');

    // Recibe el ID de la reserva a eliminar, enviado por POST desde el frontend
    $id = $_POST["id"];

    // Consulta preparada para evitar inyección SQL
    // El ? es un placeholder que se reemplaza de forma segura con bind_param
    $sql = "DELETE FROM reservas WHERE id_reserva = ?";
    $stmt = $conexion->prepare($sql);
    // "i" indica que el parámetro es de tipo integer
    $stmt->bind_param("i", $id);

    // execute() ejecuta la consulta, affected_rows verifica que se haya eliminado al menos una fila
    // Si affected_rows es 0, el ID no existía en la tabla
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo json_encode(['exito' => true, 'mensaje' => 'Reserva eliminada correctamente']);
    } else {
        echo json_encode(['exito' => false, 'mensaje' => 'No se pudo eliminar la reserva']);
    }

    // Libera los recursos del statement y cierra la conexión a la base de datos
    $stmt->close();
    $conexion->close();
?>