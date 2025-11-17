<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Content-Type: application/json");

// Conexion a Neon PostgreSQL
$host = "ep-noisy-queen-a8kas79l-pooler.eastus2.azure.neon.tech";
$dbname = "neondb";
$user = "neondb_owner";
$pass = "npg_sh28JeOpwWTI";
$sslmode = "require";

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname;sslmode=$sslmode", $user, $pass);
} catch (Exception $e) {
    echo json_encode(["error" => "Error de conexión: " . $e->getMessage()]);
    exit;
}

$method = $_SERVER["REQUEST_METHOD"];
$input = json_decode(file_get_contents("php://input"), true);

// ------------------------------------------
// 1. REGISTRO DE USUARIO
// ------------------------------------------
if ($method === "POST" && isset($input["action"]) && $input["action"] === "register") {
    $nombre = $input["nombre"] ?? "";
    $correo = $input["correo"] ?? "";
    $contrasena = password_hash($input["contrasena"] ?? "", PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO tabla_registro (nombre, correo, contrasena) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $correo, $contrasena]);

        echo json_encode(["status" => "ok", "message" => "Usuario registrado"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "El correo ya existe"]);
    }
    exit;
}

// ------------------------------------------
// 2. LOGIN DE USUARIO
// ------------------------------------------
if ($method === "POST" && isset($input["action"]) && $input["action"] === "login") {
    $correo = $input["correo"] ?? "";
    $contrasena = $input["contrasena"] ?? "";

    $stmt = $pdo->prepare("SELECT * FROM tabla_registro WHERE correo = ?");
    $stmt->execute([$correo]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($contrasena, $user["contrasena"])) {
        echo json_encode(["status" => "ok", "message" => "Login correcto"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Credenciales incorrectas"]);
    }
    exit;
}

// ------------------------------------------
// 3. CRUD TAREAS
// ------------------------------------------
switch ($method) {

    // LEER TAREAS
    case "GET":
        $stmt = $pdo->query("SELECT * FROM tareas ORDER BY id ASC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    // CREAR TAREA
    case "POST":
        $titulo = $input["titulo"] ?? "";
        $descripcion = $input["descripcion"] ?? "";
        $fecha = $input["fecha"] ?? "";

        $stmt = $pdo->prepare("INSERT INTO tareas (titulo, descripcion, fecha, completada) VALUES (?, ?, ?, false)");
        $stmt->execute([$titulo, $descripcion, $fecha]);

        echo json_encode(["status" => "ok"]);
        break;

    // ACTUALIZAR TAREA
    case "PUT":
        $id = $input["id"] ?? 0;
        $completada = $input["completada"] ?? false;

        $stmt = $pdo->prepare("UPDATE tareas SET completada=? WHERE id=?");
        $stmt->execute([$completada, $id]);

        echo json_encode(["status" => "ok"]);
        break;

    // ELIMINAR TAREA
    case "DELETE":
        $id = $input["id"] ?? 0;

        $stmt = $pdo->prepare("DELETE FROM tareas WHERE id=?");
        $stmt->execute([$id]);

        echo json_encode(["status" => "ok"]);
        break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
}
?>
