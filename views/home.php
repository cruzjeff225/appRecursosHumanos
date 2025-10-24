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
    <title>Inicio</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <style>
        .home-hero-outer {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
        }
        .home-avatar {
            width: 80px; height: 80px;
            background: #b6c3c5;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 12px rgba(255, 224, 102, 0.15);
        }
        .home-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border-radius: 1.25rem;
        }
        .home-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        #container { height: 400px; }
    </style>
</head>

<body>
    <?php include_once('../views/nav.php'); ?>

    <div class="home-hero-outer">
        <div class="container">
            <div class="text-center mb-5">
                <div class="home-avatar mx-auto mb-3">
                    <i class="fa fa-address-card fa-3x text-white"></i>
                </div>
                <h1 class="display-5 fw-bold text-primary">Bienvenido</h1>
                <p class="lead text-secondary">Sistema de Gestión de Recursos Humanos</p>
            </div>

            <!-- Formulario -->
            <form id="formDepto" method="POST" class="mb-4 text-center">
                <label for="departamento" class="form-label fw-semibold">Seleccione un departamento:</label>
                <select name="departamento" id="departamento" class="form-select w-auto d-inline-block mx-2">
                    <option value="">Todos</option>
                    <?php
                    $queryDeptos = "SELECT DISTINCT departamento FROM personal ORDER BY departamento";
                    $deptos = mysqli_query($con, $queryDeptos);
                    while ($row = mysqli_fetch_assoc($deptos)) {
                        $selected = (isset($_POST['departamento']) && $_POST['departamento'] === $row['departamento']) ? 'selected' : '';
                        echo "<option value='{$row['departamento']}' $selected>{$row['departamento']}</option>";
                    }
                    ?>
                </select>
            </form>

            <!-- Gráfico -->
            <figure class="highcharts-figure">
                <div id="container"></div>
            </figure>

            <!-- Tarjetas -->
            <div class="row g-4 justify-content-center mt-4">
                <div class="col-md-4">
                    <a href="../views/usuarios.php" class="home-card card border-0 shadow-lg text-decoration-none h-100">
                        <div class="card-body text-center py-4">
                            <i class="fa fa-user fa-2x text-success mb-3"></i>
                            <h5 class="fw-bold text-dark">Gestión de Usuarios</h5>
                            <p class="text-secondary mb-0">Consulta, edita y administra usuarios.</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="../views/personal.php" class="home-card card border-0 shadow-lg text-decoration-none h-100">
                        <div class="card-body text-center py-4">
                            <i class="fa fa-users fa-2x text-primary mb-3"></i>
                            <h5 class="fw-bold text-dark">Gestión de Talento Humano</h5>
                            <p class="text-secondary mb-0">Consulta, edita y administra el personal.</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

<?php
// Generar datos dinámicos
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
    $valores[] = $fila['total'];
}
?>

<script>
Highcharts.chart('container', {
    chart: { type: 'column' },
    title: { text: 'Cantidad de personas registradas por departamento' },
    exporting: { enabled: false },
    xAxis: {
        categories: <?= json_encode($categorias) ?>,
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: { text: 'Cantidad de personas' }
    },
    tooltip: {
        valueSuffix: ' personas'
    },
    series: [{
        name: 'Departamentos',
        data: <?= json_encode($valores, JSON_NUMERIC_CHECK) ?>
    }]
});
</script>

<script>
document.getElementById("departamento").addEventListener("change", function() {
    const departamento = this.value;

    fetch("getDataDepto.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "departamento=" + encodeURIComponent(departamento)
    })
    .then(res => res.json())
    .then(data => {
        Highcharts.chart('container', {
            chart: { type: 'column' },
            title: { text: 'Cantidad de personas registradas por departamento' },
            exporting: { enabled: false },
            xAxis: {
                categories: data.categorias,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: { text: 'Cantidad de personas' }
            },
            tooltip: { valueSuffix: ' personas' },
            series: [{
                name: 'Departamentos',
                data: data.valores
            }]
        });
    });
});
</script>

</body>
</html>
