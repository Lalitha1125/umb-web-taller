<?php
require_once 'dp.php';

function obtenerTareas() {
    global $conexion;
    $stmt = $conexion->prepare("SELECT * FROM tareas ORDER BY id DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function crearTarea($titulo, $descripcion, $fecha) {
    global $conexion;
    $stmt = $conexion->prepare("INSERT INTO tareas (titulo, descripcion, fecha) VALUES (:titulo, :descripcion, :fecha)");
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->execute();
    return $conexion->lastInsertId();
}

function actualizarTarea($id, $completada) {
    global $conexion;
    $stmt = $conexion->prepare("UPDATE tareas SET completada=:completada WHERE id=:id");
    $stmt->bindParam(':completada', $completada, PDO::PARAM_BOOL);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

function borrarTarea($id) {
    global $conexion;
    $stmt = $conexion->prepare("DELETE FROM tareas WHERE id=:id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}
?>
