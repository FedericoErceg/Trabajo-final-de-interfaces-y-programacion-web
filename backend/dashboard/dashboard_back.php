<?php
    // Headers para comunicación con el frontend Vue
    header('Access-Control-Allow-Origin: *');        // Permite peticiones desde localhost:5173 (CORS)
    header('Content-Type: application/json');         // Respuesta en formato JSON

    // Conexión a la base de datos
    require_once('../conexion.php');

    // Fechas necesarias para las consultas
    $hoy = date('Y-m-d');           // Fecha de hoy en formato YYYY-MM-DD
    $mes_inicio = date('Y-m-01');   // Primer día del mes actual (ej: 2026-11-01)
    $mes_fin = date('Y-m-t');       // Último día del mes actual (ej: 2026-11-30, 't' calcula el último día)

    // ---- STAT CARD 1: Habitaciones ocupadas hoy ----
    // Cuenta cuántas reservas activas (pendientes o confirmadas) tienen al día de hoy dentro de su rango de fechas
    // La lógica es: fecha_entrada <= hoy (ya empezó) AND fecha_salida > hoy (todavía no terminó)
    $sql1 = "SELECT COUNT(*) as ocupadas FROM reservas WHERE estado IN ('confirmada','pendiente') AND fecha_entrada <= ? AND fecha_salida > ?";
    $stmt1 = $conexion->prepare($sql1);
    $stmt1->bind_param("ss", $hoy, $hoy);
    $stmt1->execute();
    
    // get_result()->fetch_assoc()['ocupadas'] encadena: obtiene resultado → lo lee como array → saca el valor
    $ocupadas = $stmt1->get_result()->fetch_assoc()['ocupadas'];
    $stmt1->close();

    // ---- STAT CARD 1 (denominador): Total de habitaciones disponibles ----
    // No necesita prepare porque no recibe parámetros del usuario
    $totalHab = $conexion->query("SELECT COUNT(*) as total FROM habitaciones WHERE estado = 'disponible'")->fetch_assoc()['total'];

    // ---- STAT CARD 2: Reservas pendientes ----
    // Cuenta todas las reservas en estado 'pendiente' (sin importar la fecha)
    $pendientes = $conexion->query("SELECT COUNT(*) as total FROM reservas WHERE estado = 'pendiente'")->fetch_assoc()['total'];

    // ---- STAT CARD 3: Check-ins de hoy ----
    // Cuenta cuántas reservas tienen su fecha_entrada exactamente hoy
    $sql3 = "SELECT COUNT(*) as total FROM reservas WHERE fecha_entrada = ? AND estado IN ('confirmada','pendiente')";
    $stmt3 = $conexion->prepare($sql3);
    $stmt3->bind_param("s", $hoy);
    $stmt3->execute();
    $checkins = $stmt3->get_result()->fetch_assoc()['total'];
    $stmt3->close();

    // ---- STAT CARD 4: Ingresos del mes ----
    // Suma el campo 'total' de todas las reservas confirmadas o finalizadas del mes actual
    // COALESCE(SUM(total), 0) devuelve 0 en vez de NULL si no hay reservas en el mes
    $sql4 = "SELECT COALESCE(SUM(total), 0) as ingresos FROM reservas WHERE estado IN ('confirmada','finalizada') AND fecha_entrada >= ? AND fecha_entrada <= ?";
    $stmt4 = $conexion->prepare($sql4);
    $stmt4->bind_param("ss", $mes_inicio, $mes_fin);
    $stmt4->execute();
    $ingresos = $stmt4->get_result()->fetch_assoc()['ingresos'];
    $stmt4->close();

    // ---- TABLA: Últimas 5 reservas ----
    // JOIN con habitaciones para traer el número de habitación
    // LIMIT 5 trae solo las 5 más recientes, ordenadas por fecha de creación descendente
    $sql5 = "SELECT r.*, h.numero FROM reservas r INNER JOIN habitaciones h ON r.id_habitacion = h.id_habitacion ORDER BY r.fecha_creacion DESC LIMIT 5";
    $resultado5 = $conexion->query($sql5);
    $ultimas = [];
    while ($fila = $resultado5->fetch_assoc()) {
        $ultimas[] = $fila;
    }

    // ---- BARRAS: Disponibilidad por categoría ----
    // Consulta compleja que para cada categoría cuenta:
    // - total: cuántas habitaciones disponibles tiene esa categoría (LEFT JOIN para incluir categorías sin habitaciones)
    // - ocupadas: cuántas de esas habitaciones tienen una reserva activa hoy
    // COUNT(CASE WHEN ... THEN 1 END) cuenta condicionalmente: solo suma 1 cuando la habitación está en una reserva activa
    // La subconsulta busca habitaciones con reserva activa hoy (misma lógica de solapamiento de fechas)
    // GROUP BY agrupa los resultados por categoría para tener una fila por tipo
    $sql6 = "SELECT c.tipo, c.id_categoria,
             COUNT(h.id_habitacion) as total,
             COUNT(CASE WHEN h.id_habitacion IN (
                SELECT r.id_habitacion FROM reservas r 
                WHERE r.estado IN ('confirmada','pendiente') 
                AND r.fecha_entrada <= '$hoy' AND r.fecha_salida > '$hoy'
             ) THEN 1 END) as ocupadas
             FROM categorias_habitacion c
             LEFT JOIN habitaciones h ON c.id_categoria = h.id_categoria_habitacion AND h.estado = 'disponible'
             GROUP BY c.id_categoria, c.tipo";
    $resultado6 = $conexion->query($sql6);
    $disponibilidad = [];
    while ($fila = $resultado6->fetch_assoc()) {
        $disponibilidad[] = $fila;
    }

    // Devuelve TODOS los datos del dashboard en un único JSON
    // El frontend (DashboardView.vue) lo recibe con un solo fetch y actualiza this.stats
    // Así se hace una sola petición HTTP en vez de 6 separadas
    echo json_encode([
        'ocupadas' => $ocupadas,
        'totalHabitaciones' => $totalHab,
        'pendientes' => $pendientes,
        'checkins' => $checkins,
        'ingresos' => $ingresos,
        'ultimasReservas' => $ultimas,
        'disponibilidad' => $disponibilidad
    ]);

    $conexion->close();
?>