<?php
// CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json");

// ================================
// 🔹 CONEXIÓN A NEON
// ================================
$host = "ep-noisy-queen-a8kas79l-pooler.eastus2.azure.neon.tech";
$dbname = "neondb";
$user = "neondb_owner";
$pass = "npg_w6zdkliax2Sj";

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=5432;dbname=$dbname;sslmode=require",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (Exception $e) {
    echo json_encode(["error" => "Error de conexión: " . $e->getMessage()]);
    exit;
}

$method = $_SERVER["REQUEST_METHOD"];
$input = json_decode(file_get_contents("php://input"), true) ?? [];

// =======================================================
// 🔹 1. REGISTRO DE USUARIO
// =======================================================
if ($method === "POST" && ($input["action"] ?? "") === "register") {
    $nombre = $input["nombre"] ?? "";
    $email = $input["email"] ?? "";
    $password = password_hash($input["password"] ?? "", PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios_login (nombre, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $email, $password]);

        echo json_encode(["status" => "ok", "message" => "Usuario registrado"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "El correo ya existe"]);
    }
    exit;
}

// =======================================================
// 🔹 2. LOGIN DE USUARIO
// =======================================================
if ($method === "POST" && ($input["action"] ?? "") === "login") {

    $email = $input["email"] ?? "";
    $password = $input["password"] ?? "";

    $stmt = $pdo->prepare("SELECT * FROM usuarios_login WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        echo json_encode(["status" => "ok", "message" => "Login correcto"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Credenciales incorrectas"]);
    }
    exit;
}

// =======================================================
// 🔹 3. CRUD TAREAS
// =======================================================
switch ($method) {

    case "GET":
        $stmt = $pdo->query("SELECT * FROM tareas ORDER BY id ASC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case "POST":
        // Evitar que choque con login/registro
        if (isset($input["titulo"])) {
            $titulo = $input["titulo"];
            $descripcion = $input["descripcion"] ?? "";
            $fecha = $input["fecha"] ?? "";

            $stmt = $pdo->prepare("INSERT INTO tareas (titulo, descripcion, fecha, completada) VALUES (?, ?, ?, false)");
            $stmt->execute([$titulo, $descripcion, $fecha]);

            echo json_encode(["status" => "ok"]);
        }
        break;

    case "PUT":
        $id = $input["id"] ?? 0;
        $completada = $input["completada"] ?? false;

        $stmt = $pdo->prepare("UPDATE tareas SET completada=? WHERE id=?");
        $stmt->execute([$completada, $id]);

        echo json_encode(["status" => "ok"]);
        break;

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
