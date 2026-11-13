<?php
    session_start();    // Se puede sacar, no se usa en la versión Vue
    header('Access-Control-Allow-Origin: *');        // Permite peticiones desde localhost:5173 (CORS)
    header('Content-Type: application/json');         // Respuesta en formato JSON

    // Conexión a la base de datos
    require_once('../conexion.php');

    // Recibe los datos del formulario enviados por POST desde ModalHabitacionComponent.vue
    // $id es el id_habitacion de la habitación que se está editando
    $id = $_POST["id"];
    $num = trim($_POST["numero"]);          // trim() limpia espacios en blanco
    $piso = trim($_POST["piso"]);
    $cat = $_POST["id_categoria_habitacion"];    // FK a categorias_habitacion
    $est = $_POST["estado"];                     // 'disponible' o 'mantenimiento'

    // Verificación de número duplicado excluyendo la habitación actual (AND id_habitacion != ?)
    // Sin esa exclusión, la propia habitación se detectaría como duplicado al editarla
    $sqlCheck = "SELECT numero FROM habitaciones WHERE numero = ? AND id_habitacion != ?";
    $stmtCheck = $conexion->prepare($sqlCheck);

    // "si" = string (numero), integer (id)
    $stmtCheck->bind_param("si", $num, $id);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    // Si num_rows > 0, ya existe OTRA habitación con ese número → no se puede usar
    if($stmtCheck->num_rows > 0){
        echo json_encode(['exito' => false, 'mensaje' => 'Ya existe otra habitación con ese número']);
    }
    else{

        // No hay duplicados → se puede actualizar
        // UPDATE con prepared statement para modificar los datos de la habitación
        $sql = "UPDATE habitaciones SET numero = ?, piso = ?, id_categoria_habitacion = ?, estado = ? WHERE id_habitacion = ?";
    
        $stmt = $conexion->prepare($sql);

        // "siisi" = string (numero), integer (piso), integer (categoría), string (estado), integer (id)
        $stmt->bind_param("siisi", $num, $piso, $cat, $est, $id);
    
        if($stmt->execute()){
            echo json_encode(['exito' => true, 'mensaje' => 'Habitacion modificada correctamente']);
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