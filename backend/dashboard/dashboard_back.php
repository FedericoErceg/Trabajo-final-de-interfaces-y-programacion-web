<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    require_once('../conexion.php');

    $hoy = date('Y-m-d');
    $mes_inicio = date('Y-m-01');
    $mes_fin = date('Y-m-t');

    // Habitaciones ocupadas hoy
    $sql1 = "SELECT COUNT(*) as ocupadas FROM reservas WHERE estado IN ('confirmada','pendiente') AND fecha_entrada <= ? AND fecha_salida > ?";
    $stmt1 = $conexion->prepare($sql1);
    $stmt1->bind_param("ss", $hoy, $hoy);
    $stmt1->execute();
    $ocupadas = $stmt1->get_result()->fetch_assoc()['ocupadas'];
    $stmt1->close();

    // Total habitaciones
    $totalHab = $conexion->query("SELECT COUNT(*) as total FROM habitaciones WHERE estado = 'disponible'")->fetch_assoc()['total'];

    // Reservas pendientes
    $pendientes = $conexion->query("SELECT COUNT(*) as total FROM reservas WHERE estado = 'pendiente'")->fetch_assoc()['total'];

    // Check-ins hoy
    $sql3 = "SELECT COUNT(*) as total FROM reservas WHERE fecha_entrada = ? AND estado IN ('confirmada','pendiente')";
    $stmt3 = $conexion->prepare($sql3);
    $stmt3->bind_param("s", $hoy);
    $stmt3->execute();
    $checkins = $stmt3->get_result()->fetch_assoc()['total'];
    $stmt3->close();

    // Ingresos del mes
    $sql4 = "SELECT COALESCE(SUM(total), 0) as ingresos FROM reservas WHERE estado IN ('confirmada','finalizada') AND fecha_entrada >= ? AND fecha_entrada <= ?";
    $stmt4 = $conexion->prepare($sql4);
    $stmt4->bind_param("ss", $mes_inicio, $mes_fin);
    $stmt4->execute();
    $ingresos = $stmt4->get_result()->fetch_assoc()['ingresos'];
    $stmt4->close();

    // Últimas 5 reservas
    $sql5 = "SELECT r.*, h.numero FROM reservas r INNER JOIN habitaciones h ON r.id_habitacion = h.id_habitacion ORDER BY r.fecha_creacion DESC LIMIT 5";
    $resultado5 = $conexion->query($sql5);
    $ultimas = [];
    while ($fila = $resultado5->fetch_assoc()) {
        $ultimas[] = $fila;
    }

    // Disponibilidad por categoría
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