<?php
session_start();
$cities = $_SESSION['cities'] ?? [];
$matrix = $_SESSION['costMatrix'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Miguel Aponte y Cristian Morales - Problema del Viajero</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1, h2, h3 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
            box-shadow: 0 2px 3px rgba(0,0,0,0.1);
        }
        th {
            background-color: #3498db;
            color: white;
            padding: 12px;
            text-align: center;
        }
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            background-color: white;
        }
        .highlight {
            background-color: #f1c40f;
            font-weight: bold;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        input, select, button {
            padding: 8px 12px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #2980b9;
        }
        .route-info {
            background-color: #e8f4fc;
            padding: 15px;
            border-left: 4px solid #3498db;
            margin: 20px 0;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .delete-btn {
            background-color: #e74c3c;
        }
        .delete-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<h1>Miguel Aponte y Cristian Morales - Problema del Viajero</h1>

<div class="route-configuration">
    <h2>Configuración de la Matriz de Costos</h2>
    <form action="generar.php" method="post">
        <div>
            <label>Número de ciudades (2-20):</label>
            <input type="number" name="numCities" min="2" max="20" required>
        </div>
        <div>
            <label>Costo mínimo:</label>
            <input type="number" name="minCost" required>
        </div>
        <div>
            <label>Costo máximo:</label>
            <input type="number" name="maxCost" required>
        </div>
        <button type="submit">Generar Matriz</button>
    </form>
</div>

<?php if (!empty($matrix) && !empty($_SESSION['mostrarMatriz'])): ?>

    <div class="cost-matrix-section">
        <h2>Matriz de Costos entre Ciudades</h2>

        <?php if (!empty($_SESSION['mejorRuta']) && isset($_SESSION['menorCosto'])): ?>
            <div class="route-info">
                <h3>Ruta Óptima Encontrada</h3>
                <p><strong>Recorrido:</strong> <?= implode(' → ', $_SESSION['mejorRuta']) ?></p>
                <p><strong>Costo total:</strong> $<?= number_format($_SESSION['menorCosto'], 2) ?></p>
            </div>
        <?php endif; ?>

        <form action="guardar.php" method="post">
            <table>
                <thead>
                <tr>
                    <th>Desde/Hacia</th>
                    <?php foreach ($cities as $city) echo "<th>$city</th>"; ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($matrix as $from => $row): ?>
                    <tr>
                        <th><?= $from ?></th>
                        <?php foreach ($row as $to => $cost): ?>
                            <?php
                            $isHighlighted = false;
                            if (!empty($_SESSION['mejorRuta'])) {
                                $ruta = $_SESSION['mejorRuta'];
                                for ($k = 0; $k < count($ruta) - 1; $k++) {
                                    $actual = strtoupper(trim($ruta[$k]));
                                    $siguiente = strtoupper(trim($ruta[$k + 1]));
                                    if ($actual === strtoupper($from) && $siguiente === strtoupper($to)) {
                                        $isHighlighted = true;
                                        break;
                                    }
                                }
                            }
                            ?>
                            <td <?= $isHighlighted ? 'class="highlight"' : '' ?>>
                                <input type="number" name="matrix[<?= $from ?>][<?= $to ?>]" value="<?= $cost ?>" min="0" style="width:60px;">
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <br>
            <button type="submit">Guardar Cambios</button>
        </form>
    </div>

    <div class="route-calculation">
        <h2>Cálculo de Ruta Óptima</h2>
        <form action="calcular.php" method="post">
            <div>
                <label>Ciudad origen:</label>
                <select name="origen" required>
                    <?php foreach ($cities as $city) echo "<option value=\"$city\">$city</option>"; ?>
                </select>
            </div>
            <div>
                <label>Ciudad destino:</label>
                <select name="destino" required>
                    <?php foreach ($cities as $city) echo "<option value=\"$city\">$city</option>"; ?>
                </select>
            </div>
            <button type="submit">Calcular Ruta Óptima</button>
        </form>
    </div>

<?php endif; ?>

<div class="saved-routes">
    <h2>Historial de Rutas Optimizadas</h2>
    <?php
    require 'db.php';
    $pdo = getPDO();
    $stmt = $pdo->query("SELECT * FROM rutas ORDER BY fecha_guardado DESC");
    $rutas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($rutas):
        ?>
        <table>
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Recorrido</th>
                <th>Costo Total</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rutas as $ruta): ?>
                <tr>
                    <td><?= htmlspecialchars($ruta['nombre']) ?></td>
                    <td><?= $ruta['ruta'] ?></td>
                    <td>$<?= number_format($ruta['costo_total'], 2) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($ruta['fecha_guardado'])) ?></td>
                    <td>
                        <div class="action-buttons">
                            <form action="cargar.php" method="post">
                                <input type="hidden" name="ruta" value="<?= $ruta['ruta'] ?>">
                                <input type="hidden" name="costo" value="<?= $ruta['costo_total'] ?>">
                                <input type="hidden" name="matrix_serializada" value='<?= htmlspecialchars($ruta['matriz'], ENT_QUOTES, 'UTF-8') ?>'>
                                <button type="submit">Cargar</button>
                            </form>
                            <form action="eliminar.php" method="post" onsubmit="return confirm('¿Confirmas que deseas eliminar esta ruta?');">
                                <input type="hidden" name="id" value="<?= $ruta['id'] ?>">
                                <button type="submit" class="delete-btn">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No se han guardado rutas todavía.</p>
    <?php endif; ?>
</div>

<?php unset($_SESSION['mostrarMatriz']); ?>

</body>
</html>