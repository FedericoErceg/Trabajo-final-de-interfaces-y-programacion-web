<?php

    session_start();
    header('Content-Type: application/json');

    require_once('../conexion.php');

    $tipoHab = ucwords(trim($_POST["tipo"]));
    $sqlConsulta = "SELECT tipo FROM categorias_habitacion WHERE tipo = ?";
    $stmtConsulta = $conexion->prepare($sqlConsulta);
    $stmtConsulta->bind_param("s", $tipoHab);
    $stmtConsulta->execute();
    $stmtConsulta->store_result();

    if($stmtConsulta->num_rows == 0){
        $capacidad = $_POST["capacidad"];
        $precio_n = $_POST["precio_noche"];
        $descripcion = trim($_POST["descripcion"]);

        $sql = "INSERT INTO categorias_habitacion 
        (tipo, 
        capacidad, 
        precio_noche, 
        descripcion) 
        VALUES (?,?,?,?)";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sids", $tipoHab, $capacidad, $precio_n, $descripcion);
        
        if($stmt->execute()){
            echo json_encode(['exito' => true, 'mensaje' => 'Categoria de habitación registrada correctamente']);
        }
        else{
            echo json_encode(['exito' => false, 'mensaje' => 'Error al registrar: ' . $stmt->error]);
        }
        
        $stmt->close();
    }
    else{
        echo json_encode(['exito' => false, 'mensaje' => 'Categoria de habitación ya se encuentra registrada']);
    }

    $stmtConsulta->close();
    $conexion->close();

?>