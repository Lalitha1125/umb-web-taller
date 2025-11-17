<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json");

require_once "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$usuario = $data["usuario"] ?? "";
$password = $data["password"] ?? "";

// Validar usuario en BD
$sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND password = '$password'";
$res = mysqli_query($conexion, $sql);

if (mysqli_num_rows($res) > 0) {
    echo json_encode([
        "status" => "ok",
        "mensaje" => "Login exitoso",
        "usuario" => $usuario
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "mensaje" => "Credenciales inválidas"
    ]);
}
?>
