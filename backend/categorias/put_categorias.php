<?php
    session_start();    // Se puede sacar, no se usa en la versión Vue
    // Headers para comunicación con el frontend Vue
    header('Access-Control-Allow-Origin: *');        // Permite peticiones desde localhost:5173 (CORS)
    header('Content-Type: application/json');         // Respuesta en formato JSON

    // Conexión a la base de datos
    require_once("../conexion.php");

    // Recibe los datos del formulario enviados por POST desde ModalCategoriaComponent.vue
    // $id es el id_categoria de la categoría que se está editando
    $id = $_POST["id"];
    
    // ucwords() capitaliza la primera letra de cada palabra (ej: "doble simple" → "Doble Simple")
    // trim() elimina espacios en blanco al inicio y final
    $tipo = ucwords(trim($_POST["tipo"]));
    $capacidad = $_POST["capacidad"];
    $precio_n = $_POST["precio_noche"];
    $descripcion = $_POST["descripcion"];

    // Verificación de tipo duplicado excluyendo la categoría actual (AND id_categoria != ?)
    // Sin esa exclusión, la propia categoría se detectaría como duplicado al editarla
    $sqlCheck = "SELECT tipo FROM categorias_habitacion WHERE tipo = ? AND id_categoria != ?";
    $stmtCheck = $conexion->prepare($sqlCheck);

    // "si" = string (tipo), integer (id)
    $stmtCheck->bind_param("si", $tipo, $id);
    $stmtCheck->execute();

    // store_result() almacena el resultado en memoria para poder usar num_rows
    $stmtCheck->store_result();

    // Si num_rows > 0, ya existe OTRA categoría con ese tipo → no se puede usar
    if($stmtCheck->num_rows > 0){
        echo json_encode(['exito' => false, 'mensaje' => 'Ya existe un mismo tipo de categoria']);
    }
    else{

        // No hay duplicados → se puede actualizar
        // UPDATE con prepared statement para modificar los datos de la categoría
        $sql = "UPDATE categorias_habitacion SET tipo = ?, capacidad = ?, precio_noche = ?, descripcion = ? WHERE id_categoria = ?";
        $stmt = $conexion->prepare($sql);

        // "sidsi" = string (tipo), integer (capacidad), double (precio), string (descripcion), integer (id)
        // double (d) porque el precio puede tener decimales
        $stmt->bind_param("sidsi", $tipo, $capacidad, $precio_n, $descripcion, $id);
        
        if($stmt->execute()){
            echo json_encode(['exito' => true, 'mensaje' => 'Categoria modificada correctamente']);
        }
        else{
            echo json_encode(['exito' => false, 'mensaje' => 'Error al modificar: ' . $stmt->error]);
        }
        
        // Cierra el statement del UPDATE (solo existe si entró al else)
        $stmt->close();
    }

    // Libera recursos del check y cierra la conexión
    $stmtCheck->close();
    $conexion->close();
?>