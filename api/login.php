<?php
session_start();
header('Content-Type: application/json');

$datos = json_decode(file_get_contents('php://input'), true);

if(isset($datos['usuario'])){
    $_SESSION['usuario'] = $datos['usuario'];
    echo json_encode(['mensaje' => 'Sesión iniciada', 'usuario' => $_SESSION['usuario']]);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Usuario requerido']);
}
?>
