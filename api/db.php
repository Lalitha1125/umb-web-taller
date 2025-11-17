<?php
// db.php - Conexión a la base de datos (Postgres usando pg_* o PDO).
// Vamos a usar PDO para Postgres (más seguro y moderno).

// Cargar variables de entorno (Render / Codespaces deben definirlas)
$host = getenv('DB_HOST') ?: 'ep-noisy-queen-a8kas79l-pooler.eastus2.azure.neon.tech';
$port = getenv('DB_PORT') ?: '5432';
$db   = getenv('DB_DATABASE') ?: 'neondb';
$user = getenv('DB_USERNAME') ?: 'neondb_owner';
$pass = getenv('DB_PASSWORD') ?: 'npg_sh28JeOpwWTI';
$sslmode = getenv('DB_SSLMODE') ?: 'require';

$dsn = "pgsql:host={$host};port={$port};dbname={$db};sslmode={$sslmode}";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    // Salida simple en caso de error
    http_response_code(500);
    echo json_encode(['error' => 'Error al conectar a la base de datos: ' . $e->getMessage()]);
    exit;
}
?>
