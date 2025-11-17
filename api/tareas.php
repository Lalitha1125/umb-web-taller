<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

require_once 'modelo.php';

$metodo = $_SERVER['REQUEST_METHOD'];
$datos = json_decode(file_get_contents('php://input'), true);

switch ($metodo) {
    case 'GET':
        echo json_encode(obtenerTareas());
        break;

    case 'POST':
        if (isset($datos['titulo'], $datos['descripcion'], $datos['fecha'])) {
            crearTarea($datos['titulo'], $datos['descripcion'], $datos['fecha']);
            echo json_encode(['mensaje' => 'Tarea creada']);
        } else {
            echo json_encode(['mensaje' => 'Titulo, descripcion y fecha requeridos']);
        }
        break;

    case 'PUT':
        if (isset($datos['id'], $datos['completada'])) {
            actualizarTarea($datos['id'], $datos['completada']);
            echo json_encode(['mensaje' => 'Tarea actualizada']);
        } else {
            echo json_encode(['mensaje' => 'ID y completada requeridos']);
        }
        break;

    case 'DELETE':
        if (isset($datos['id'])) {
            borrarTarea($datos['id']);
            echo json_encode(['mensaje' => 'Tarea eliminada']);
        } else {
            echo json_encode(['mensaje' => 'ID requerido']);
        }
        break;

    case 'OPTIONS':
        http_response_code(200);
        break;

    default:
        http_response_code(405);
        echo json_encode(['mensaje' => 'Método no permitido']);
        break;
}
?>
