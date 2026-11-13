<?php
    // Headers para comunicación con el frontend Vue
    header('Content-Type: application/json');         // Respuesta en formato JSON
    header('Access-Control-Allow-Origin: *');         // Permite peticiones desde localhost:5173 (CORS)
    
    // Conexión a la base de datos
    require_once('../conexion.php');

    // SELECT con INNER JOIN para traer los datos de cada habitación junto con su categoría
    // h.* trae todas las columnas de la tabla habitaciones (id, numero, piso, estado, id_categoria)
    // c.tipo, c.capacidad, c.precio_noche trae los datos de la categoría asociada
    // El JOIN vincula cada habitación con su categoría a través de la FK id_categoria_habitacion
    $sql = "SELECT h.*, c.tipo, c.capacidad, c.precio_noche 
            FROM habitaciones h 
            INNER JOIN categorias_habitacion c 
            ON h.id_categoria_habitacion = c.id_categoria";

    // query() ejecuta la consulta directamente (sin prepare porque no recibe parámetros del usuario)
    $resultado = $conexion->query($sql);
    $habitaciones = [];     // Array donde se acumulan las filas

    // fetch_assoc() devuelve cada fila como array asociativo (clave => valor)
    // El while recorre todas las filas y las agrega al array
    while ($fila = $resultado->fetch_assoc()) {
        $habitaciones[] = $fila;
    }

    // json_encode() convierte el array PHP a JSON
    // El frontend (HabitacionesView.vue) lo recibe con res.json() y lo guarda en this.habitaciones
    echo json_encode($habitaciones);
    $conexion->close();
?>