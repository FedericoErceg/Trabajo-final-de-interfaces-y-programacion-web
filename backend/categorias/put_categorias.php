<?php

    session_start();
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    require_once("../conexion.php");

    $id = $_POST["id"];
    $tipo = ucwords(trim($_POST["tipo"]));
    $capacidad = $_POST["capacidad"];
    $precio_n = $_POST["precio_noche"];
    $descripcion = $_POST["descripcion"];

    //verificacion de tipo duplicado
    $sqlCheck = "SELECT tipo FROM categorias_habitacion WHERE tipo = ? AND 	id_categoria != ?";
    $stmtCheck = $conexion->prepare($sqlCheck);
    $stmtCheck->bind_param("si", $tipo, $id);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if($stmtCheck->num_rows > 0){
        echo json_encode(['exito' => false, 'mensaje' => 'Ya existe un mismo tipo de categoria']);
    }
    else{
        $sql = "UPDATE categorias_habitacion SET tipo = ?, capacidad = ?, precio_noche = ?, descripcion = ? WHERE id_categoria = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sidsi", $tipo, $capacidad, $precio_n, $descripcion, $id);
        
        if($stmt->execute()){
            echo json_encode(['exito' => true, 'mensaje' => 'Categoria modificada correctamente']);
        }
        else{
            echo json_encode(['exito' => false, 'mensaje' => 'Error al modificar: ' . $stmt->error]);
        }
        
        $stmt->close();
    }

    $stmtCheck->close();
    $conexion->close();
?>