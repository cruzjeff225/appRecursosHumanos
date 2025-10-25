<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

include_once '../config/config.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <style>
        #container {
            height: 420px;
        }

        .chart-card {
            border-radius: 1rem;
        }
    </style>
</head>

<body>
    <?php
    include_once('nav.php');
    ?>
    </br>
    </br>
    <div class="container py-4">
        <h2 class="mb-3">Dashboard de Empleados por Departamento</h2>

        <!-- Formulario de filtro -->
        <form id="formDepto" method="POST" class="mb-3">
            <label for="departamento" class="form-label">Filtrar por departamento:</label>
            <select name="departamento" id="departamento" class="form-select w-auto d-inline-block ms-2">
                <option value="">Todos</option>
                <?php
                $queryDeptos = "SELECT DISTINCT departamento FROM personal ORDER BY departamento";
                $deptos = mysqli_query($con, $queryDeptos);
                while ($row = mysqli_fetch_assoc($deptos)) {
                    $selected = (isset($_POST['departamento']) && $_POST['departamento'] === $row['departamento']) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($row['departamento'], ENT_QUOTES, 'UTF-8') . "' $selected>" . htmlspecialchars($row['departamento'], ENT_QUOTES, 'UTF-8') . "</option>";
                }
                ?>
            </select>
        </form>

        <!-- Contenedor de la gráfica -->
        <div class="card shadow-sm chart-card p-3">
            <div id="container"></div>
        </div>
    </div>

    <?php
    // Datos iniciales (servidor) para cuando la página cargue por primera vez
    $condicion = "";
    if (!empty($_POST['departamento'])) {
        $departamento = mysqli_real_escape_string($con, $_POST['departamento']);
        $condicion = "WHERE departamento = '$departamento'";
    }

    $query = "SELECT departamento, COUNT(*) AS total FROM personal $condicion GROUP BY departamento";
    $resultado = mysqli_query($con, $query);

    $categorias = [];
    $valores = [];
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $categorias[] = $fila['departamento'];
        $valores[] = (int)$fila['total'];
    }
    ?>

    <script>
        // Gráfica inicial usando datos proporcionados por PHP
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Cantidad de empleados por departamento'
            },
            exporting: {
                enabled: false
            },
            xAxis: {
                categories: <?= json_encode($categorias) ?>,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Cantidad de personas'
                }
            },
            tooltip: {
                valueSuffix: ' personas'
            },
            series: [{
                name: 'Departamentos',
                data: <?= json_encode($valores, JSON_NUMERIC_CHECK) ?>
            }]
        });

        // Actualizar gráfica cuando se cambia el select usa la vista getDataDepto.php
        document.getElementById("departamento").addEventListener("change", function() {
            const departamento = this.value;

            fetch("getDataDepto.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "departamento=" + encodeURIComponent(departamento)
                })
                .then(res => res.json())
                .then(data => {
                    Highcharts.chart('container', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Cantidad de personas registradas por departamento'
                        },
                        exporting: {
                            enabled: false
                        },
                        xAxis: {
                            categories: data.categorias,
                            crosshair: true
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Cantidad de personas'
                            }
                        },
                        tooltip: {
                            valueSuffix: ' personas'
                        },
                        series: [{
                            name: 'Departamentos',
                            data: data.valores
                        }]
                    });
                })
                .catch(err => console.error('Error cargando datos:', err));
        });
    </script>

</body>

</html>