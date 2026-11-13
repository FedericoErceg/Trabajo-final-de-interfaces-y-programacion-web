<?php
    session_start();    // Se puede sacar, no se usa en la versión Vue
    // Headers para comunicación con el frontend Vue
    header('Access-Control-Allow-Origin: *');        // Permite peticiones desde localhost:5173 (CORS)
    header('Content-Type: application/json');         // Respuesta en formato JSON

    // Conexión a la base de datos
    require_once('../conexion.php');

    // Recibe el tipo de categoría y lo formatea
    // trim() elimina espacios en blanco al inicio y final
    // ucwords() capitaliza la primera letra de cada palabra (ej: "doble simple" → "Doble Simple")
    $tipoHab = ucwords(trim($_POST["tipo"]));

    // Verificación de duplicado: busca si ya existe una categoría con ese tipo
    $sqlConsulta = "SELECT tipo FROM categorias_habitacion WHERE tipo = ?";
    $stmtConsulta = $conexion->prepare($sqlConsulta);

    // "s" indica que el parámetro es de tipo string
    $stmtConsulta->bind_param("s", $tipoHab);
    $stmtConsulta->execute();

    // store_result() almacena el resultado en memoria para poder usar num_rows
    $stmtConsulta->store_result();

    // Si num_rows == 0, no existe una categoría con ese tipo → se puede insertar
    if($stmtConsulta->num_rows == 0){

        // Recibe el resto de los datos solo si pasó la validación de duplicado
        $capacidad = $_POST["capacidad"];
        $precio_n = $_POST["precio_noche"];
        $descripcion = trim($_POST["descripcion"]);

        // INSERT con prepared statement
        // 4 campos: tipo, capacidad, precio_noche, descripcion
        $sql = "INSERT INTO categorias_habitacion 
        (tipo, 
        capacidad, 
        precio_noche, 
        descripcion) 
        VALUES (?,?,?,?)";
        
        $stmt = $conexion->prepare($sql);
        
        // "sids" = string (tipo), integer (capacidad), double (precio_noche), string (descripcion)
        // double (d) porque el precio puede tener decimales
        $stmt->bind_param("sids", $tipoHab, $capacidad, $precio_n, $descripcion);
        
        if($stmt->execute()){
            echo json_encode(['exito' => true, 'mensaje' => 'Categoria de habitación registrada correctamente']);
        }
        else{
            echo json_encode(['exito' => false, 'mensaje' => 'Error al registrar: ' . $stmt->error]);
        }
        
        // Cierra el statement del INSERT (solo existe si entró al if)
        $stmt->close();
    }
    else{
        // Ya existe una categoría con ese tipo → no se puede crear otra
        echo json_encode(['exito' => false, 'mensaje' => 'Categoria de habitación ya se encuentra registrada']);
    }

    // Libera recursos del check y cierra la conexión
    $stmtConsulta->close();
    $conexion->close();

?>