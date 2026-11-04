<?php

    require_once('../conexion.php');

    $sql = "SELECT * FROM categorias_habitacion";

    $resultado = $conexion->query($sql);

?>                  