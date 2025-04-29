<?php
session_start();

// Limpiar valores anteriores para evitar mostrar rutas antiguas
unset($_SESSION['mejorRuta']);
unset($_SESSION['menorCosto']);

// Parámetros recibidos
$numCities = $_POST['numCities'] ?? 5;
$minCost = $_POST['minCost'] ?? 10;
$maxCost = $_POST['maxCost'] ?? 100;

$letters = range('A', 'T');
$cities = array_slice($letters, 0, $numCities);

// Generar matriz de costos
$matrix = [];

foreach ($cities as $i => $cityFrom) {
    foreach ($cities as $j => $cityTo) {
        $matrix[$cityFrom][$cityTo] = ($i === $j) ? 0 : rand($minCost, $maxCost);
    }
}

// Guardar en sesión
$_SESSION['cities'] = $cities;
$_SESSION['costMatrix'] = $matrix;
$_SESSION['mostrarMatriz'] = true;

header('Location: index.php');
exit;
