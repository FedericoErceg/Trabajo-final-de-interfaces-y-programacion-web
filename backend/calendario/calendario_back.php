<?php
    // Headers para comunicación con el frontend Vue
    header('Access-Control-Allow-Origin: *');        // Permite peticiones desde localhost:5173 (CORS)
    header('Content-Type: application/json');         // Respuesta en formato JSON

    // Conexión a la base de datos
    require_once('../conexion.php');

    // Recibe mes y año por GET (query params en la URL)
    // Si no se envían, usa el mes y año actual como valor por defecto
    // isset() verifica si el parámetro existe, intval() lo convierte a entero
    $mes = isset($_GET['mes']) ? intval($_GET['mes']) : intval(date('m'));
    $anio = isset($_GET['anio']) ? intval($_GET['anio']) : intval(date('Y'));

    // cal_days_in_month() calcula cuántos días tiene el mes (28, 29, 30 o 31)
    // CAL_GREGORIAN indica que usa el calendario gregoriano estándar
    // Este valor se usa en el frontend para generar las columnas de la grilla
    $diasEnMes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);

    // ---- HABITACIONES: trae todas las habitaciones con su categoría ----
    // estado_hab es un alias para distinguirlo del estado de las reservas en el frontend
    // ORDER BY h.numero ordena las filas de la grilla por número de habitación
    $sqlHab = "SELECT h.id_habitacion, h.numero, h.estado as estado_hab, c.tipo 
               FROM habitaciones h 
               INNER JOIN categorias_habitacion c ON h.id_categoria_habitacion = c.id_categoria 
               ORDER BY h.numero";
    $resultHab = $conexion->query($sqlHab);
    $habitaciones = [];
    while ($fila = $resultHab->fetch_assoc()) {
        $habitaciones[] = $fila;
    }

    // ---- RESERVAS DEL MES: trae las reservas que caen dentro del mes seleccionado ----
    // Arma las fechas de inicio y fin del mes en formato YYYY-MM-DD
    // str_pad() agrega un 0 adelante si el mes tiene un solo dígito (ej: 3 → 03)
    $inicio = "$anio-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-01";          // Primer día del mes
    $fin = "$anio-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-$diasEnMes";     // Último día del mes

    // Trae las reservas activas (pendientes o confirmadas) que se solapan con el mes
    // La lógica de solapamiento es: fecha_entrada <= fin del mes AND fecha_salida > inicio del mes
    // Esto incluye reservas que empiezan antes del mes pero terminan dentro, o que cruzan todo el mes
    $sqlRes = "SELECT id_habitacion, fecha_entrada, fecha_salida, nombre, apellido, estado 
               FROM reservas 
               WHERE estado IN ('pendiente','confirmada') 
               AND fecha_entrada <= ? AND fecha_salida > ?";
    $stmtRes = $conexion->prepare($sqlRes);
    
    // "ss" = string (fin), string (inicio) — las fechas se pasan como strings en formato YYYY-MM-DD
    $stmtRes->bind_param("ss", $fin, $inicio);
    $stmtRes->execute();

    // get_result() permite usar fetch_assoc() para leer las filas
    $resultRes = $stmtRes->get_result();
    $reservas = [];
    while ($fila = $resultRes->fetch_assoc()) {
        $reservas[] = $fila;
    }
    $stmtRes->close();

    // Devuelve todos los datos del calendario en un único JSON
    // El frontend (CalendarioView.vue) recibe esto y se lo pasa a GrillaCalendarioComponent
    // que usa habitaciones para las filas, reservas para colorear las celdas y diasEnMes para las columnas
    echo json_encode([
        'mes' => $mes,                  // Mes seleccionado (para que el frontend lo confirme)
        'anio' => $anio,                // Año seleccionado
        'diasEnMes' => $diasEnMes,      // Cantidad de días del mes (para generar las columnas)
        'habitaciones' => $habitaciones, // Array de habitaciones (filas de la grilla)
        'reservas' => $reservas          // Array de reservas activas (para colorear las celdas)
    ]);

    $conexion->close();
?>