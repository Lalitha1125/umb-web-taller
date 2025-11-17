<?php
// index.php - endpoint principal para /api
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *"); // para desarrollo. En producción restringir.
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Manejar preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'modelo.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = isset($_GET['path']) ? $_GET['path'] : '';

if ($method === 'GET') {
    // Obtener todas las tareas
    $tareas = obtenerTareas();
    echo json_encode($tareas);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if ($method === 'POST') {
    // crear tarea
    if (!isset($input['titulo']) || trim($input['titulo']) === '') {
        http_response_code(400);
        echo json_encode(['error' => 'Falta el título']);
        exit;
    }
    $id = crearTarea($input['titulo']);
    echo json_encode(['mensaje' => 'Tarea creada', 'id' => $id]);
    exit;
}

if ($method === 'PUT') {
    // actualizar tarea: se espera id y campos a cambiar
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Falta id']);
        exit;
    }
    $ok = actualizarTarea($input['id'], $input['titulo'] ?? null, $input['completada'] ?? null);
    echo json_encode(['ok' => $ok]);
    exit;
}

if ($method === 'DELETE') {
    // borrar tarea: id como query param o en body
    $id = $_GET['id'] ?? ($input['id'] ?? null);
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Falta id']);
        exit;
    }
    $ok = borrarTarea($id);
    echo json_encode(['ok' => $ok]);
    exit;
}

// Si llega acá, método no permitido
http_response_code(405);
echo json_encode(['error' => 'Método no permitido']);
?>
