<?php
// Conexión a Neon (Postgres)
$host = "ep-noisy-queen-a8kas79l-pooler.eastus2.azure.neon.tech";
$db   = "neondb";
$user = "neondb_owner";
$pass = "npg_D65naqhwbcgd"; // ¡Tu contraseña real!
$port = "5432";

// Conexión usando PDO
try {
    $conexion = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit();
}
?>
