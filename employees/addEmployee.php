<?php
session_start();
if (isset($_SESSION['usuario']) == null) {
    header("Location: ../views/index.php");
}
include_once '../config/config.php';
// Variables para capturar los datos del formulario
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : "";
$teléfono = isset($_POST['Teléfono']) ? $_POST['Teléfono'] : "";
$DUI = isset($_POST['DUI']) ? $_POST['DUI'] : "";
$fechaNacimiento = isset($_POST['fechaNacimiento']) ? $_POST['fechaNacimiento'] : "";
$departamento = isset($_POST['departamento']) ? $_POST['departamento'] : "";
$distrito = isset($_POST['distrito']) ? $_POST['distrito'] : "";
$coloniaResidencia = isset($_POST['coloniaResidencia']) ? $_POST['coloniaResidencia'] : "";
$calleResidencia = isset($_POST['calleResidencia']) ? $_POST['calleResidencia'] : "";
$casaResidencia = isset($_POST['casaResidencia']) ? $_POST['casaResidencia'] : "";
$estadoCivil = isset($_POST['estadoCivil']) ? $_POST['estadoCivil'] : "";
$fotografía = isset($_POST['fotografía']) ? $_POST['fotografía'] : "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Agregar Usuario</title>
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
    <div class="contenido">
        <form action="" method="POST" class="form-control">
            <div class="form-group col-md-6">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" placeholder="Ingresa el nombre del empleado" name="nombre" value="<?php echo $nombre ?>">
            </div>
            <div class="form-group">
                <label for="Teléfono">Teléfono</label>
                <input type="text" class="form-control" id="Teléfono" name="Teléfono" value="<?php echo $teléfono ?>">
            </div>
            <div class="form-group">
                <label for="DUI">DUI</label>
                <input type="text" class="form-control" id="DUI" name="DUI" value="<?php echo $DUI ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="fechaNacimiento">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" value="<?php echo $fechaNacimiento ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="departamento">Departamento</label>
                <select id="departamento" class="form-select" name="departamento" value="<?php echo $departamento ?>">
                    <option selected>Seleccionar...</option>
                    <option>Ahuchapán</option>
                    <option>Cabañas</option>
                    <option>Chalatenango</option>
                    <option>Cuscatlán</option>
                    <option>La Libertad</option>
                    <option>La Paz</option>
                    <option>La Unión</option>
                    <option>Morazán</option>
                    <option>San Miguel</option>
                    <option>San Vicente</option>
                    <option>Santa Ana</option>
                    <option>Sonsonate</option>
                    <option>Usulután</option>
                </select>
            </div>
            <div class="form-group">
                <label for="distrito">Distrito</label>
                <input type="text" class="form-control" id="distrito" name="distrito" value="<?php echo $distrito ?>">
            </div>
            <!-- Dirección -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="coloniaResidencia">Colonia</label>
                    <input type="text" class="form-control" id="coloniaResidencia" name="coloniaResidencia" value="<?php echo $coloniaResidencia ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="calleResidencia">Calle</label>
                    <input type="text" class="form-control" id="calleResidencia" name="calleResidencia" value="<?php echo $calleResidencia ?>">
                </div>
                <div class="form-group col-md-2">
                    <label for="casaResidencia">Casa</label>
                    <input type="text" class="form-control" id="casaResidencia" name="casaResidencia" value="<?php echo $casaResidencia ?>">
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="estadoCivil">Estado Civil</label>
                <select id="estadoCivil" class="form-select" name="estadoCivil" value="<?php echo $estadoCivil ?>">
                    <option selected>Seleccionar...</option>
                    <option>Soltero</option>
                    <option>Casado</option>
                    <option>Divorciado</option>
                    <option>Viudo</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="fotografía" class="form-label">Fotografía</label>
                <input class="form-control form-control-sm" id="fotografía" type="file" name="fotografía" value="<?php echo $fotografía ?>">
            </div>
            <br>
            <input type="submit" class="form-control btn btn-primary" value="Registrar">
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
    $fotografía = isset($_POST['fotografía']) ? $_POST['fotografía'] : '';


    // Verificar si el número de DUI de empleado ya existe
    $verificar_dui = "SELECT * FROM personal WHERE DUI='$DUI'";
    $resultado = mysqli_query($con, $verificar_dui);
    if (mysqli_num_rows($resultado) >= 1) {
        echo "<script>alert(\"El número de DUI de usuario ya existe.\");</script>";
    } else {
        // Insertar nuevo empleado en la base de datos
        $insertar = "INSERT INTO personal (nombre, Telefono, DUI, fechaNacimiento, departamento, distrito, coloniaResidencia, calleResidencia, casaResidencia, estadoCivil, fotografía) VALUES ('$nombre', '$teléfono', '$DUI', '$fechaNacimiento', '$departamento', '$distrito', '$coloniaResidencia', '$calleResidencia', '$casaResidencia', '$estadoCivil', '$fotografía')";
        if (mysqli_query($con, $insertar)) {
            echo "<script>alert(\"Usuario registrado exitosamente.\"); window.location.href = '../views/usuarios.php';</script>";
        } else {
            echo "<script>alert(\"Error al registrar el usuario: " . mysqli_error($con) . "\"); window.location.href = 'addEmployee.php';</script>";
        }
    }
    // Cerrar la conexión a la base de datos
    mysqli_close($con);
}
?>