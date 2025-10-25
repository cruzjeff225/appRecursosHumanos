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

    </br>
    <div class="container py-5">
        <!-- Header / Hero -->
        <div class="row align-items-center mb-4">
            <div class="col-auto">
                <div class="home-avatar d-flex align-items-center justify-content-center">
                    <i class="fa fa-address-card fa-2x text-white"></i>
                </div>
            </div>
            <div class="col">
                <h1 class="h3 mb-0 text-primary">Bienvenido</h1>
                <p class="text-muted mb-0 fw-bold">Sistema de Gestión de Recursos Humanos</p>
            </div>
        </div>

        <!-- Gráfico en tarjeta -->
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2">
                <div>
                    <strong>Gráfica por departamento</strong>
                    <div class="text-muted small">Cantidad de empleados registradas por departamento</div>
                </div>

                <form id="formDepto" method="POST" class="ms-md-3">
                    <label for="departamento" class="form-label visually-hidden">Departamento</label>
                    <div class="d-flex align-items-center gap-2">
                        <select name="departamento" id="departamento" class="form-select form-select-sm">
                            <option value="">Todos</option>
                            <?php
                            $queryDeptos = "SELECT DISTINCT departamento FROM personal ORDER BY departamento";
                            $deptos = mysqli_query($con, $queryDeptos);
                            while ($row = mysqli_fetch_assoc($deptos)) {
                                $opt = htmlspecialchars($row['departamento'], ENT_QUOTES, 'UTF-8');
                                $selected = (isset($_POST['departamento']) && $_POST['departamento'] === $row['departamento']) ? 'selected' : '';
                                echo "<option value='" . $opt . "' $selected>" . $opt . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div id="container"></div>
            </div>
        </div>

        <!-- Texto introductorio para tarjetas -->
        <div class="mb-3">
            <h3 class="h5 mb-1">Accesos rápidos</h3>
            <p class="text-muted mb-0">Utiliza las tarjetas para ir rápidamente a las secciones más usadas. Aquí encontrarás accesos directos para gestionar usuarios y personal.</p>
        </div>

        <!-- Tarjetas de acceso rápido -->
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <a href="../views/usuarios.php" class="card home-card h-100 text-decoration-none text-body shadow-sm">
                    <div class="card-body text-center py-4">
                        <i class="fa fa-user fa-2x text-success mb-3"></i>
                        <h5 class="fw-bold">Gestión de Usuarios</h5>
                        <p class="text-muted mb-0">Consulta, edita y administra usuarios.</p>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-4">
                <a href="../views/personal.php" class="card home-card h-100 text-decoration-none text-body shadow-sm">
                    <div class="card-body text-center py-4">
                        <i class="fa fa-users fa-2x text-primary mb-3"></i>
                        <h5 class="fw-bold">Gestión de Talento Humano</h5>
                        <p class="text-muted mb-0">Consulta, edita y administra el personal.</p>
                    </div>
                </a>
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
        title: { text: 'Cantidad de empleados' }
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