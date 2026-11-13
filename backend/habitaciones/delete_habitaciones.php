<?php
    // Headers para comunicación con el frontend Vue
    header('Access-Control-Allow-Origin: *');        // Permite peticiones desde localhost:5173 (CORS)
    header('Content-Type: application/json');         // Respuesta en formato JSON

    session_start();    // Se puede sacar, no se usa en la versión Vue (el login es con Firebase)

    // Conexión a la base de datos
    require_once('../conexion.php');

    // Recibe el ID de la habitación a eliminar, enviado por POST desde el frontend
    $id = $_POST["id"];

    // Consulta preparada para evitar inyección SQL
    $sql = "DELETE FROM habitaciones WHERE id_habitacion = ?";
    $stmt = $conexion->prepare($sql);
    // "i" indica que el parámetro es de tipo integer
    $stmt->bind_param("i", $id);

    // execute() ejecuta la consulta
    // affected_rows verifica que se haya eliminado al menos una fila
    // Si la habitación tiene reservas asociadas, la FK con RESTRICT impide el borrado
    // y affected_rows será 0, entrando al else
    if($stmt->execute() && $stmt->affected_rows > 0){
        echo json_encode(['exito' => true, 'mensaje' => 'Habitación eliminada correctamente']);
    }
    else{
        // Puede fallar si: el ID no existe, o la habitación tiene reservas (FK RESTRICT)
        echo json_encode(['exito' => false, 'mensaje' => 'No se pudo eliminar. Puede tener reservas asociadas.']);
    }

    // Libera recursos y cierra la conexión
    $stmt->close();
    $conexion->close();

?>