<?php
require_once 'db.php';

// CREATE
function crearTarea($titulo) {
    global $pdo;
    $titulo_seguro = htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8');
    $stmt = $pdo->prepare("INSERT INTO tareas (titulo) VALUES (:titulo)");
    $stmt->execute([':titulo' => $titulo_seguro]);
    return $pdo->lastInsertId();
}

// READ
function obtenerTareas() {
    global $pdo;
    $stmt = $pdo->query("SELECT id, titulo, completada FROM tareas ORDER BY id DESC");
    return $stmt->fetchAll();
}

// UPDATE
function actualizarTarea($id, $titulo = null, $completada = null) {
    global $pdo;
    $updates = [];
    $params = [':id' => $id];
    if (!is_null($titulo)) {
        $updates[] = "titulo = :titulo";
        $params[':titulo'] = htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8');
    }
    if (!is_null($completada)) {
        $updates[] = "completada = :completada";
        $params[':completada'] = ($completada ? 'true' : 'false');
    }
    if (empty($updates)) return false;
    $sql = "UPDATE tareas SET " . implode(", ", $updates) . " WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($params);
}

// DELETE
function borrarTarea($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM tareas WHERE id = :id");
    return $stmt->execute([':id' => $id]);
}
?>
