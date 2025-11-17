<?php
require_once 'db.php'; // Archivo de conexión PDO a tu base de datos

// Obtener todas las tareas
function obtenerTareas() {
    global $conexion;
    $stmt = $conexion->query("SELECT * FROM tareas ORDER BY fecha_creacion DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Crear nueva tarea
function crearTarea($titulo, $descripcion) {
    global $conexion;
    $stmt = $conexion->prepare("INSERT INTO tareas (titulo, descripcion, fecha_creacion) VALUES (?, ?, NOW()) RETURNING *");
    $stmt->execute([$titulo, $descripcion]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Actualizar tarea (completada)
function actualizarTarea($id, $completada) {
    global $conexion;
    $stmt = $conexion->prepare("UPDATE tareas SET completada = ? WHERE id = ?");
    $stmt->execute([$completada, $id]);
}

// Borrar tarea
function borrarTarea($id) {
    global $conexion;
    $stmt = $conexion->prepare("DELETE FROM tareas WHERE id = ?");
    $stmt->execute([$id]);
}
?>
