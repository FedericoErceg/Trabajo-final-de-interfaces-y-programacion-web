<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    require_once('../conexion.php');

    $mes = isset($_GET['mes']) ? intval($_GET['mes']) : intval(date('m'));
    $anio = isset($_GET['anio']) ? intval($_GET['anio']) : intval(date('Y'));

    $diasEnMes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);

    // Habitaciones
    $sqlHab = "SELECT h.id_habitacion, h.numero, h.estado as estado_hab, c.tipo 
               FROM habitaciones h 
               INNER JOIN categorias_habitacion c ON h.id_categoria_habitacion = c.id_categoria 
               ORDER BY h.numero";
    $resultHab = $conexion->query($sqlHab);
    $habitaciones = [];
    while ($fila = $resultHab->fetch_assoc()) {
        $habitaciones[] = $fila;
    }

    // Reservas del mes
    $inicio = "$anio-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-01";
    $fin = "$anio-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-$diasEnMes";

    $sqlRes = "SELECT id_habitacion, fecha_entrada, fecha_salida, nombre, apellido, estado 
               FROM reservas 
               WHERE estado IN ('pendiente','confirmada') 
               AND fecha_entrada <= ? AND fecha_salida > ?";
    $stmtRes = $conexion->prepare($sqlRes);
    $stmtRes->bind_param("ss", $fin, $inicio);
    $stmtRes->execute();
    $resultRes = $stmtRes->get_result();
    $reservas = [];
    while ($fila = $resultRes->fetch_assoc()) {
        $reservas[] = $fila;
    }
    $stmtRes->close();

    echo json_encode([
        'mes' => $mes,
        'anio' => $anio,
        'diasEnMes' => $diasEnMes,
        'habitaciones' => $habitaciones,
        'reservas' => $reservas
    ]);

    $conexion->close();
?>