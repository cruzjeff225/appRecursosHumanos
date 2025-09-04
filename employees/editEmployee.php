<?php
session_start();
if (isset($_SESSION['usuario']) == null) {
    header("Location: ../views/index.php");
}
include_once '../config/config.php';

// Obtener el ID del usuario a editar desde la URL
$idPersonal = isset($_GET['idPersonal']) ? $_GET['idPersonal'] : '';
// Consulta para obtener los datos actuales del usuario
$consulta = "SELECT * FROM personal WHERE idPersonal='$idPersonal'";
$ejecutar_consulta = mysqli_query($con, $consulta);
$datos = mysqli_fetch_array($ejecutar_consulta);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Editar Usuario</title>
    <style>
        .contenido {
            margin: 40px;
        }
    </style>
</head>

<body>
    <?php
    include_once('../views/nav.php');
    ?>
    </br>
    <div class="container mt-5">
        <h1 class="mb-4 text-center fw-bold">Editar Empleado</h1>
        <form action="" method="POST" class="form-control shadow-sm p-4">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nombre" class="form-label fw-bold">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $datos['nombre']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="Teléfono" class="form-label fw-bold">Teléfono</label>
                    <input type="text" class="form-control" id="Teléfono" name="Teléfono" value="<?php echo $datos['Telefono']; ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="DUI" class="form-label fw-bold">DUI</label>
                    <input type="text" class="form-control" id="DUI" name="DUI" value="<?php echo $datos['DUI']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="fechaNacimiento" class="form-label fw-bold">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" value="<?php echo $datos['fechaNacimiento']; ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="departamento" class="form-label fw-bold">Departamento</label>
                    <select id="departamento" class="form-select" name="departamento" required>
                        <option selected><?php echo $datos['departamento']; ?></option>
                        <option>Ahuachapán</option>
                        <option>Cabañas</option>
                        <option>Chalatenango</option>
                        <option>Cuscatlán</option>
                        <option>La Libertad</option>
                        <option>La Paz</option>
                        <option>La Unión</option>
                        <option>Morazán</option>
                        <option>San Miguel</option>
                        <option>San Vicente</option>
                        <option>San Salvador</option>
                        <option>Santa Ana</option>
                        <option>Sonsonate</option>
                        <option>Usulután</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="distrito" class="form-label fw-bold">Distrito</label>
                    <input type="text" class="form-control" id="distrito" name="distrito" value="<?php echo $datos['distrito']; ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="coloniaResidencia" class="form-label fw-bold">Colonia</label>
                    <input type="text" class="form-control" id="coloniaResidencia" name="coloniaResidencia" value="<?php echo $datos['coloniaResidencia']; ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="calleResidencia" class="form-label fw-bold">Calle</label>
                    <input type="text" class="form-control" id="calleResidencia" name="calleResidencia" value="<?php echo $datos['calleResidencia']; ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="casaResidencia" class="form-label fw-bold">Casa</label>
                    <input type="text" class="form-control" id="casaResidencia" name="casaResidencia" value="<?php echo $datos['casaResidencia']; ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="estadoCivil" class="form-label fw-bold">Estado Civil</label>
                    <select id="estadoCivil" class="form-select" name="estadoCivil" required>
                        <option selected><?php echo $datos['estadoCivil']; ?></option>
                        <option>Soltero</option>
                        <option>Casado</option>
                        <option>Divorciado</option>
                        <option>Viudo</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="fotografía" class="form-label fw-bold">Fotografía</label>
                    <input class="form-control" id="fotografía" type="file" name="fotografía">
                    <?php if (!empty($datos['fotografía'])): ?>
                        <small class="text-muted">Imagen actual: <?php echo $datos['fotografía']; ?></small>
                    <?php endif; ?>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</body>

</html>

<?php
// Verificar si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar datos del formulario
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
    $teléfono = isset($_POST['Teléfono']) ? $_POST['Teléfono'] : '';
    $DUI = isset($_POST['DUI']) ? $_POST['DUI'] : '';
    $fechaNacimiento = isset($_POST['fechaNacimiento']) ? $_POST['fechaNacimiento'] : '';
    $departamento = isset($_POST['departamento']) ? $_POST['departamento'] : '';
    $distrito = isset($_POST['distrito']) ? $_POST['distrito'] : '';
    $coloniaResidencia = isset($_POST['coloniaResidencia']) ? $_POST['coloniaResidencia'] : '';
    $calleResidencia = isset($_POST['calleResidencia']) ? $_POST['calleResidencia'] : '';
    $casaResidencia = isset($_POST['casaResidencia']) ? $_POST['casaResidencia'] : '';
    $estadoCivil = isset($_POST['estadoCivil']) ? $_POST['estadoCivil'] : '';
    // Manejo de la fotografía. Si no se sube una nueva en edición se mantiene la anterior
    $fotografía = isset($_POST['fotografía']) && !empty($_POST['fotografía']) ? $_POST['fotografía'] : $datos['fotografía'];

    $estado_empleado = "SELECT DUI FROM personal WHERE idPersonal='$idPersonal'";
    $verificar_sql = mysqli_query($con, $estado_empleado);
    $verificar_datos = mysqli_fetch_assoc($verificar_sql);
    $var_dui = $verificar_datos['DUI'];

    // Si no hay cambios redirigir sin actualizar
    if($nombre === $datos['nombre'] && $teléfono === $datos['Teléfono'] && $DUI === $datos['DUI'] && $fechaNacimiento === $datos['fechaNacimiento'] && $departamento === $datos['departamento'] && $distrito === $datos['distrito'] && $coloniaResidencia === $datos['coloniaResidencia'] && $calleResidencia === $datos['calleResidencia'] && $casaResidencia === $datos['casaResidencia'] && $estadoCivil === $datos['estadoCivil'] && $fotografía === $datos['fotografía']) {
        echo "<script>alert('No realizaste ningún cambio en los datos.'); window.location.href = '../views/personal.php';</script>";
    } else {
        // Verificar si el número de DUI ya existe
        $verificar_dui = "SELECT idPersonal FROM personal WHERE DUI='$DUI' AND idPersonal != '$idPersonal'";
        $resultado_dui = mysqli_query($con, $verificar_dui);
        if (mysqli_num_rows($resultado_dui) > 0) {
            echo "<script>alert('El número de DUI ya está en uso. Por favor, elige otro.'); window.location.href = 'editEmployee.php?idPersonal=$idPersonal';</script>";
            exit();
        }

        // Actualizar los datos del empleado en la base de datos
        $actualizar = "UPDATE personal SET nombre='$nombre', Telefono='$teléfono', DUI='$DUI', fechaNacimiento='$fechaNacimiento', departamento='$departamento', distrito='$distrito', coloniaResidencia='$coloniaResidencia', calleResidencia='$calleResidencia', casaResidencia='$casaResidencia', estadoCivil='$estadoCivil', fotografía='$fotografía' WHERE idPersonal='$idPersonal'";
        if (mysqli_query($con, $actualizar)) {
            echo "<script>alert('Empleado actualizado exitosamente.'); window.location.href = '../views/personal.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar el empleado: " . mysqli_error($con) . "'); window.location.href = 'editEmployee.php?idPersonal=$idPersonal';</script>";
        }
    }
    // Cerrar la conexión a la base de datos
    mysqli_close($con);
}