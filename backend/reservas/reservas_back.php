<?php
    // Headers para comunicación con el frontend Vue
    header('Access-Control-Allow-Origin: *');        // Permite peticiones desde localhost:5173 (CORS)
    header('Content-Type: application/json');         // Respuesta en formato JSON

    // Conexión a la base de datos
    require_once('../conexion.php');

    // SELECT con INNER JOIN para traer los datos de la reserva junto con el número de habitación
    // r.* trae todas las columnas de la tabla reservas
    // h.numero trae el número de habitación de la tabla habitaciones (para mostrarlo en la tabla del frontend)
    // ORDER BY fecha_creacion DESC ordena las reservas más recientes primero
    $sql = "SELECT r.*, h.numero 
            FROM reservas r 
            INNER JOIN habitaciones h ON r.id_habitacion = h.id_habitacion
            ORDER BY r.fecha_creacion DESC";
    
    // query() ejecuta la consulta directamente (no necesita prepare porque no recibe parámetros del usuario)
    $resultado = $conexion->query($sql);
    $reservas = [];     // Array donde se van acumulando las filas

    // fetch_assoc() devuelve cada fila como un array asociativo (clave => valor)
    // El while recorre todas las filas y las agrega al array $reservas
    while($fila = $resultado->fetch_assoc()){
        $reservas[] = $fila;    // [] agrega el elemento al final del array
    }

    // json_encode() convierte el array PHP a formato JSON para que el frontend lo parsee
    // El frontend (ReservasView.vue) lo recibe con res.json() y lo guarda en this.reservas
    echo json_encode($reservas);
    $conexion->close();

?>