<?php
session_start();

if (isset($_POST['ruta'], $_POST['costo'], $_POST['matrix_serializada'])) {
    // Restaurar la matriz desde la base de datos
    $matriz = unserialize($_POST['matrix_serializada']);

    $_SESSION['costMatrix'] = $matriz;
    $_SESSION['mejorRuta'] = preg_split('/\s*->\s*/', $_POST['ruta']); // Corrige el formato
    $_SESSION['menorCosto'] = $_POST['costo'];
    $_SESSION['cities'] = array_keys($matriz); // Asegúrate de que estén las ciudades
    $_SESSION['mostrarMatriz'] = true; // <-- Esto activa la lógica del subrayado
}

header('Location: index.php');
exit;
?>
