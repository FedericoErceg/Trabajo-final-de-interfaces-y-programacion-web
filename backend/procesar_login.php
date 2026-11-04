<?php
    session_start();
    header('Content-Type: application/json');

    $respuesta = isset($_POST["captcha"]) ? intval(trim($_POST["captcha"])) : 0;
    $accion = isset($_POST["accion"]) ? trim($_POST["accion"]) : '';

    // verificacion del capcha
    if ($accion == 'verificar_captcha') {
        if ($respuesta == $_SESSION["captcha"]) {
            unset($_SESSION["captcha"]); // no se reutiliza
            echo json_encode(['exito' => true]);
        } else {
            echo json_encode(['exito' => false, 'mensaje' => 'Captcha incorrecto']);
        }
        exit;
    }

    // creamos la sesion despues de firebase
    if ($accion == 'crear_sesion') {
        $_SESSION['usuario_logueado'] = true;
        echo json_encode(['exito' => true]);
        exit;
    }
?>