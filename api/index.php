<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

require_once 'modelo.php';

$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        echo json_encode(obtenerTareas());
        break;

    case 'POST':
        $datos = json_decode(file_get_contents('php://input'), true);
        if (isset($datos['titulo'], $datos['descripcion'])) {
            crearTarea($datos['titulo'], $datos['descripcion']);
            echo json_encode(['mensaje' => 'Tarea creada']);
        } else {
            echo json_encode(['mensaje' => 'Título y descripción requeridos']);
        }
        break;

    case 'PUT':
        $datos = json_decode(file_get_contents('php://input'), true);
        if (isset($datos['id'], $datos['completada'])) {
            actualizarTarea($datos['id'], $datos['completada']);
            echo json_encode(['mensaje' => 'Tarea actualizada']);
        } else {
            echo json_encode(['mensaje' => 'ID y completada requeridos']);
        }
        break;

    case 'DELETE':
        $datos = json_decode(file_get_contents('php://input'), true);
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
