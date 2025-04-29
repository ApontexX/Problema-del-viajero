<?php
function getPDO() {
    $host = 'localhost';
    $db   = 'viajes';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    try {
        return new PDO($dsn, $user, $pass);
    } catch (PDOException $e) {
        die('Error de conexión: ' . $e->getMessage());
    }
}
?>


