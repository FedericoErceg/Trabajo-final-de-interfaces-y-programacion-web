<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    session_start();

    require_once('../conexion.php');

    $numeroHabitacion = trim($_POST["numero"]);

    $sql = "SELECT numero FROM habitaciones WHERE numero = ?";
    $stmtConsulta = $conexion->prepare($sql);
    $stmtConsulta->bind_param("s",$numeroHabitacion);
    $stmtConsulta->execute();
    $stmtConsulta->store_result();

    if($stmtConsulta->num_rows == 0){
        $pisoHabitacion = trim($_POST["piso"]);
        $categoriaHabitacion = $_POST["id_categoria_habitacion"];
        $estadoHabitacion = $_POST["estado"];
    
        $sqlInsert = "INSERT INTO habitaciones 
        (id_categoria_habitacion, numero, piso, estado)
        VALUES
        (?,?,?,?)";
    
        $stmt = $conexion->prepare($sqlInsert);
        $stmt->bind_param("isis", $categoriaHabitacion, $numeroHabitacion, $pisoHabitacion, $estadoHabitacion);
    
        if($stmt->execute()){
            echo json_encode(['exito' => true, 'mensaje' => 'Habitacion registrada correctamente']);
        }
        else{
            echo json_encode(['exito' => false, 'mensaje' => 'Error al registrar: ' . $stmt->error]);
        }
    
        $stmt->close();
    }
    else{
        echo json_encode(['exito' => false, 'mensaje' => 'La habitacion ya se encuentra registrada']);
    }

    $stmtConsulta->close();
    $conexion->close();

?>