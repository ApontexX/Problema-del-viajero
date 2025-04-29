<?php
session_start();

$cities = $_SESSION['cities'];
$originalMatrix = $_SESSION['costMatrix'];

if (isset($_POST['matrix'])) {
    $matrix = [];
    foreach ($_POST['matrix'] as $row => $cols) {
        foreach ($cols as $col => $value) {
            $matrix[$row][$col] = (int)$value;
        }
    }
} else {
    $matrix = $originalMatrix;
}

$origen = $_POST['origen'];
$destino = $_POST['destino'];

$intermedias = array_filter($cities, fn($c) => $c !== $origen && $c !== $destino);

$rutaActual = array_values($intermedias);
$costoActual = calcularCosto($origen, $rutaActual, $destino, $matrix);

$tiempoMaximo = 5; // segundos
$inicio = time();

while ((time() - $inicio) < $tiempoMaximo) {
    $i = rand(0, count($rutaActual) - 1);
    $j = rand(0, count($rutaActual) - 1);

    if ($i == $j) continue;

    $nuevaRuta = $rutaActual;
    [$nuevaRuta[$i], $nuevaRuta[$j]] = [$nuevaRuta[$j], $nuevaRuta[$i]];

    $nuevoCosto = calcularCosto($origen, $nuevaRuta, $destino, $matrix);

    if ($nuevoCosto < $costoActual) {
        $rutaActual = $nuevaRuta;
        $costoActual = $nuevoCosto;
    }
}

$caminoCompleto = array_merge([$origen], $rutaActual, [$destino]);
?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Resultados de Optimización - Problema del Viajero</title>
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
            h2, h3 {
                color: #2c3e50;
                border-bottom: 2px solid #3498db;
                padding-bottom: 10px;
                margin-top: 30px;
            }
            .result-container {
                background: white;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                margin-bottom: 30px;
            }
            .optimal-route {
                font-size: 1.2em;
                font-weight: bold;
                color: #27ae60;
                padding: 10px;
                background-color: #e8f8f5;
                border-left: 4px solid #2ecc71;
            }
            .total-cost {
                font-size: 1.3em;
                color: #e74c3c;
                margin: 15px 0;
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
            input[type="number"] {
                width: 60px;
                padding: 8px;
                text-align: center;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            .btn {
                padding: 10px 20px;
                margin: 10px 5px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
                transition: background-color 0.3s;
            }
            .btn-primary {
                background-color: #3498db;
                color: white;
            }
            .btn-primary:hover {
                background-color: #2980b9;
            }
            .btn-success {
                background-color: #2ecc71;
                color: white;
            }
            .btn-success:hover {
                background-color: #27ae60;
            }
            .btn-default {
                background-color: #95a5a6;
                color: white;
            }
            .btn-default:hover {
                background-color: #7f8c8d;
            }
            .form-group {
                margin-bottom: 15px;
            }
            .form-group label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }
            .form-control {
                width: 100%;
                padding: 8px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
        </style>
    </head>
    <body>

    <div class="result-container">
        <h2>Resultado de la Optimización</h2>

        <div class="optimal-route">
            <strong>Ruta óptima encontrada:</strong> <?php echo implode(' → ', $caminoCompleto); ?>
        </div>

        <div class="total-cost">
            <strong>Costo total:</strong> $<?php echo number_format($costoActual, 2); ?>
        </div>
    </div>

    <div class="result-container">
        <h3>Matriz de Costos Actual</h3>
        <p>Puede editar los costos y recalcular la ruta óptima:</p>

        <form method="post" action="calcular.php">
            <input type="hidden" name="origen" value="<?php echo $origen; ?>">
            <input type="hidden" name="destino" value="<?php echo $destino; ?>">

            <table>
                <thead>
                <tr>
                    <th>Desde/Hacia</th>
                    <?php foreach (array_keys($matrix) as $col): ?>
                        <th><?php echo $col; ?></th>
                    <?php endforeach; ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($matrix as $fila => $cols): ?>
                    <tr>
                        <th><?php echo $fila; ?></th>
                        <?php foreach ($cols as $col => $valor):
                            $resaltar = false;
                            for ($i = 0; $i < count($caminoCompleto) - 1; $i++) {
                                if ($caminoCompleto[$i] === $fila && $caminoCompleto[$i + 1] === $col) {
                                    $resaltar = true;
                                    break;
                                }
                            }
                            ?>
                            <td>
                                <input
                                        type="number"
                                        name="matrix[<?php echo $fila; ?>][<?php echo $col; ?>]"
                                        value="<?php echo $valor; ?>"
                                        min="0"
                                    <?php echo $resaltar ? 'class="highlight"' : ''; ?>
                                >
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <div style="text-align: center; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Recalcular Ruta Óptima</button>
            </div>
        </form>
    </div>

    <div class="result-container">
        <h3>Opciones Adicionales</h3>

        <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
            <form method="get" action="index.php" style="flex: 1; margin: 10px;">
                <button type="submit" class="btn btn-default">Calcular Nueva Ruta</button>
            </form>

            <form method="post" action="guardar.php" style="flex: 2; margin: 10px;">
                <div class="form-group">
                    <label for="nombre_ruta">Nombre para guardar esta ruta:</label>
                    <input type="text" class="form-control" name="nombre_ruta" required>
                </div>
                <input type="hidden" name="origen" value="<?php echo $origen; ?>">
                <input type="hidden" name="destino" value="<?php echo $destino; ?>">
                <input type="hidden" name="ruta" value="<?php echo implode('->', $caminoCompleto); ?>">
                <input type="hidden" name="costo" value="<?php echo $costoActual; ?>">
                <input type="hidden" name="matrix_serializada" value="<?php echo htmlspecialchars(serialize($matrix)); ?>">
                <button type="submit" class="btn btn-success">Guardar Ruta y Matriz</button>
            </form>
        </div>
    </div>

    </body>
    </html>

<?php
function calcularCosto($origen, $ruta, $destino, $matrix) {
    $costo = 0;
    $camino = array_merge([$origen], $ruta, [$destino]);
    for ($i = 0; $i < count($camino) - 1; $i++) {
        $costo += $matrix[$camino[$i]][$camino[$i + 1]];
    }
    return $costo;
}
?>