<?php


$DB_HOST = 'localhost:3335';
$DB_NAME = 'redsocialdb';
$DB_USER = 'root';
$DB_PASS = '';

try {
    // Crear la conexión PDO
    $db = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configuración de errores
} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
    die();
}
?>
