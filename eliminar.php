<?php
session_start();
require 'db.php';

$pdo = getPDO();

if (isset($_POST['id'])) {
    $stmt = $pdo->prepare("DELETE FROM rutas WHERE id = ?");
    $stmt->execute([$_POST['id']]);
}

header('Location: index.php');
exit;
?>
