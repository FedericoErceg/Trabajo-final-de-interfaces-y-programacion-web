<?php

    session_start();
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    require_once('../conexion.php');

    $id = $_POST["id"];
    $num = trim($_POST["numero"]);
    $piso = trim($_POST["piso"]);
    $cat = $_POST["id_categoria_habitacion"];
    $est = $_POST["estado"];

    //verificacion de habitacion repetida
    $sqlCheck = "SELECT numero FROM habitaciones WHERE numero = ? AND id_habitacion != ?";
    $stmtCheck = $conexion->prepare($sqlCheck);
    $stmtCheck->bind_param("si", $num, $id);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if($stmtCheck->num_rows > 0){
        echo json_encode(['exito' => false, 'mensaje' => 'Ya existe otra habitación con ese número']);
    }
    else{
        $sql = "UPDATE habitaciones SET numero = ?, piso = ?, id_categoria_habitacion = ?, estado = ? WHERE id_habitacion = ?";
    
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("siisi", $num, $piso, $cat, $est, $id);
    
        if($stmt->execute()){
            echo json_encode(['exito' => true, 'mensaje' => 'Habitacion modificada correctamente']);
        }
        else{
            echo json_encode(['exito' => false, 'mensaje' => 'Error al modificar: ' . $stmt->error]);
        }
    
        $stmt->close();

    }

    $stmtCheck->close();
    $conexion->close();

?>