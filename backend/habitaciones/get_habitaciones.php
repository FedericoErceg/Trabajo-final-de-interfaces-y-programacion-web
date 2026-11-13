<?php
    // Headers para comunicación con el frontend Vue
    header('Access-Control-Allow-Origin: *');        // Permite peticiones desde localhost:5173 (CORS)
    header('Content-Type: application/json');         // Respuesta en formato JSON
    
    session_start();    // Se puede sacar, no se usa en la versión Vue

    // Conexión a la base de datos
    require_once('../conexion.php');

    // Recibe el ID de la habitación por GET (query param en la URL)
    // Se usa cuando el frontend necesita los datos de una habitación específica para editar
    $idHab = $_GET["id"];

    // Consulta preparada para buscar la habitación por su ID
    $sql = "SELECT numero FROM habitaciones WHERE id_habitacion = ?";
    $stmt = $conexion->prepare($sql);

    // "i" indica que el parámetro es de tipo integer
    $stmt->bind_param("i", $idHab);
    $stmt->execute();
    // get_result() obtiene el resultado como objeto para poder usar fetch_assoc()
    // A diferencia de store_result() que solo permite num_rows, get_result() permite leer los datos
    $resultado = $stmt->get_result();

    // fetch_assoc() devuelve la fila como array asociativo, o null si no encontró nada
    // Si encontró la habitación, devuelve sus datos como JSON
    if($fila = $resultado->fetch_assoc()){
        echo json_encode($fila);
    }
    else{
        // Si no existe una habitación con ese ID
        echo json_encode(['exito' => false, 'mensaje' => 'Habitación no encontrada']);
    }

    // Libera recursos y cierra la conexión
    $stmt->close();
    $conexion->close();

?>