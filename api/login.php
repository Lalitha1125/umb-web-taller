<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Leer JSON enviado desde el frontend
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data["email"]) || !isset($data["password"])) {
    echo json_encode(["success" => false, "message" => "Datos incompletos"]);
    exit;
}

$email = $data["email"];
$password = $data["password"];

// Credenciales NEON (asegúrate de usar las correctas)
$host = "ep-noisy-queen-a8kas79l-pooler.eastus2.azure.neon.tech";
$dbname = "neondb";
$user = "neondb_owner";
$pass = "TU_PASSWORD_AQUI"; // <-- cámbiala por la correcta !!
$port = "5432";
$sslmode = "require";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;sslmode=$sslmode", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si existe el usuario
    $sql = "SELECT * FROM notas_login WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["email" => $email]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userData && password_verify($password, $userData["password"])) {
        echo json_encode([
            "success" => true,
            "message" => "Login exitoso",
            "user" => [
                "id" => $userData["id"],
                "email" => $userData["email"]
            ]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Credenciales incorrectas"]);
    }

} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>
