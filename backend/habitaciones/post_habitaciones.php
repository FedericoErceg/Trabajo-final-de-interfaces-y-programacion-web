<?php
    // Headers para comunicación con el frontend Vue
    header('Access-Control-Allow-Origin: *');        // Permite peticiones desde localhost:5173 (CORS)
    header('Content-Type: application/json');         // Respuesta en formato JSON
    session_start();    // Se puede sacar, no se usa en la versión Vue

    // Conexión a la base de datos
    require_once('../conexion.php');

    // Recibe el número de habitación y limpia espacios con trim()
    $numeroHabitacion = trim($_POST["numero"]);

    // Verificación de duplicado: busca si ya existe una habitación con ese número
    // Consulta preparada con placeholder ? para evitar inyección SQL
    $sql = "SELECT numero FROM habitaciones WHERE numero = ?";
    $stmtConsulta = $conexion->prepare($sql);

    // "s" indica que el parámetro es de tipo string
    $stmtConsulta->bind_param("s", $numeroHabitacion);
    $stmtConsulta->execute();

    // store_result() almacena el resultado en memoria para poder usar num_rows
    $stmtConsulta->store_result();

    // Si num_rows == 0, no existe una habitación con ese número → se puede insertar
    if($stmtConsulta->num_rows == 0){
        // Recibe el resto de los datos del formulario solo si pasó la validación
        $pisoHabitacion = trim($_POST["piso"]);
        $categoriaHabitacion = $_POST["id_categoria_habitacion"];    // FK a categorias_habitacion
        $estadoHabitacion = $_POST["estado"];                        // 'disponible' o 'mantenimiento'
    
        // INSERT con prepared statement
        // 4 campos: id_categoria_habitacion, numero, piso, estado
        $sqlInsert = "INSERT INTO habitaciones 
        (id_categoria_habitacion, numero, piso, estado)
        VALUES
        (?,?,?,?)";
    
        $stmt = $conexion->prepare($sqlInsert);
        
        // "isis" = integer (categoría), string (número), integer (piso), string (estado)
        $stmt->bind_param("isis", $categoriaHabitacion, $numeroHabitacion, $pisoHabitacion, $estadoHabitacion);
    
        if($stmt->execute()){
            echo json_encode(['exito' => true, 'mensaje' => 'Habitacion registrada correctamente']);
        }
        else{
            echo json_encode(['exito' => false, 'mensaje' => 'Error al registrar: ' . $stmt->error]);
        }
    
        // Cierra el statement del INSERT (solo existe si entró al if)
        $stmt->close();
    }
    else{
        // Ya existe una habitación con ese número → no se puede crear otra
        echo json_encode(['exito' => false, 'mensaje' => 'La habitacion ya se encuentra registrada']);
    }

    // Libera recursos del check y cierra la conexión
    $stmtConsulta->close();
    $conexion->close();

?>