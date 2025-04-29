<?php
session_start();
require 'db.php';

$pdo = getPDO();

if (
    isset($_POST['ruta'], $_POST['costo'], $_POST['matrix_serializada'], $_POST['nombre_ruta'], $_POST['origen'], $_POST['destino'])
) {
    $nombre = $_POST['nombre_ruta'];
    $ruta = $_POST['ruta'];
    $costo = $_POST['costo'];
    $matriz = $_POST['matrix_serializada'];
    $origen = $_POST['origen'];
    $destino = $_POST['destino'];

    $stmt = $pdo->prepare("INSERT INTO rutas (nombre, origen, destino, ruta, costo_total, matriz) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $origen, $destino, $ruta, $costo, $matriz]);
}

header('Location: index.php');
exit;
