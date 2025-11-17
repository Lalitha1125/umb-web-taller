<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Usa POST']);
    exit;
}
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['usuario'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Falta usuario']);
    exit;
}
$_SESSION['usuario'] = htmlspecialchars($data['usuario'], ENT_QUOTES, 'UTF-8');
echo json_encode(['mensaje' => 'Sesión iniciada', 'usuario' => $_SESSION['usuario']]);
?>
