<?php
    session_start();    // Se puede sacar, no se usa en la versión Vue
    // Headers para comunicación con el frontend Vue
    header('Access-Control-Allow-Origin: *');        // Permite peticiones desde localhost:5173 (CORS)
    header('Content-Type: application/json');         // Respuesta en formato JSON

    // Conexión a la base de datos
    require_once('../conexion.php');

    // Recibe el ID de la categoría por GET (query param en la URL)
    // Se usa cuando el frontend necesita los datos de una categoría específica para editar
    $idCat = $_GET["id"];

    // Consulta preparada para buscar la categoría por su ID
    // SELECT * trae todas las columnas: id_categoria, tipo, capacidad, precio_noche, descripcion
    $sql = "SELECT * FROM categorias_habitacion WHERE id_categoria = ?";
    $stmt = $conexion->prepare($sql);

    // "i" indica que el parámetro es de tipo integer
    $stmt->bind_param("i", $idCat);
    $stmt->execute();
    
    // get_result() obtiene el resultado como objeto para poder usar fetch_assoc()
    $resultado = $stmt->get_result();

    // fetch_assoc() devuelve la fila como array asociativo, o null si no encontró nada
    // Si encontró la categoría, devuelve todos sus datos como JSON
    if($fila = $resultado->fetch_assoc()){
        echo json_encode($fila);
    }
    else{
        // Si no existe una categoría con ese ID
        echo json_encode(['exito' => false, 'mensaje' => 'Categoria no encontrada']);
    }

    // Libera recursos y cierra la conexión
    $stmt->close();
    $conexion->close();

?>