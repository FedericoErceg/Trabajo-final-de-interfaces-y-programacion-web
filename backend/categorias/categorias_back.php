<?php
    // Headers para comunicación con el frontend Vue
    header('Access-Control-Allow-Origin: *');        // Permite peticiones desde localhost:5173 (CORS)
    header('Content-Type: application/json');         // Respuesta en formato JSON

    // Conexión a la base de datos
    require_once('../conexion.php');

    // SELECT * trae todas las columnas de la tabla categorias_habitacion
    // No necesita prepare porque no recibe parámetros del usuario
    $sql = "SELECT * FROM categorias_habitacion";
    // query() ejecuta la consulta directamente
    $resultado = $conexion->query($sql);
    $categorias = [];       // Array donde se acumulan las filas

    // fetch_assoc() devuelve cada fila como array asociativo (clave => valor)
    // El while recorre todas las filas hasta que no queden más (devuelve null)
    while ($fila = $resultado->fetch_assoc()) {
        $categorias[] = $fila;      // [] agrega el elemento al final del array
    }

    // json_encode() convierte el array PHP a formato JSON
    // El frontend (CategoriasView.vue) lo recibe con res.json() y lo guarda en this.categorias
    echo json_encode($categorias);
    $conexion->close();
?>