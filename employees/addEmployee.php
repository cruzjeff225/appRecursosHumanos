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
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    <div class="container mt-5">
        <h1 class="mb-4 text-center fw-bold">Registro de Empleado</h1>
        <form action="" method="POST" class="form-control shadow-sm p-4" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nombre" class="form-label fw-bold">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresa el nombre del empleado" required>
                </div>
                <div class="col-md-6">
                    <label for="Teléfono" class="form-label fw-bold">Teléfono</label>
                    <input type="text" class="form-control" id="Teléfono" name="Teléfono" placeholder="Ingresa el teléfono" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="DUI" class="form-label fw-bold">DUI</label>
                    <input type="text" class="form-control" id="DUI" name="DUI" placeholder="Ingresa el DUI" required>
                </div>
                <div class="col-md-6">
                    <label for="fechaNacimiento" class="form-label fw-bold">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="departamento" class="form-label fw-bold">Departamento</label>
                    <select id="departamento" class="form-select" name="departamento" required>
                        <option selected disabled>Seleccionar...</option>
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
                    <input type="text" class="form-control" id="distrito" name="distrito" placeholder="Ingresa el distrito" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="coloniaResidencia" class="form-label fw-bold">Colonia</label>
                    <input type="text" class="form-control" id="coloniaResidencia" name="coloniaResidencia" placeholder="Ingresa la colonia" required>
                </div>
                <div class="col-md-4">
                    <label for="calleResidencia" class="form-label fw-bold">Calle</label>
                    <input type="text" class="form-control" id="calleResidencia" name="calleResidencia" placeholder="Ingresa la calle" required>
                </div>
                <div class="col-md-4">
                    <label for="casaResidencia" class="form-label fw-bold">Casa</label>
                    <input type="text" class="form-control" id="casaResidencia" name="casaResidencia" placeholder="Ingresa el número de casa" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="estadoCivil" class="form-label fw-bold">Estado Civil</label>
                    <select id="estadoCivil" class="form-select" name="estadoCivil" required>
                        <option selected disabled>Seleccionar...</option>
                        <option>Soltero</option>
                        <option>Casado</option>
                        <option>Divorciado</option>
                        <option>Viudo</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="fotografía" class="form-label fw-bold">Fotografía</label>
                    <input class="form-control" id="fotografía" type="file" name="fotografía" accept="image/*">
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Registrar
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


    // Manejar la carga de la fotografía
    $fotografía = 'user.png'; // Valor por defecto
    if(isset($_FILES['fotografía']) && $_FILES['fotografía']['error'] == 0) {
        $directorio = "../img/imgEmployees/"; // Directorio donde se guardaran las imágenes
        $tempName = $_FILES['fotografía']['tmp_name'];
        $fileName = basename($_FILES['fotografía']['name']);
        $path = $directorio . $fileName;

        // Mover el archivo subido al directorio deseado
        if(move_uploaded_file($tempName, $path)) {
            $fotografía = $fileName; // Actualizar el nombre del archivo si se subió correctamente
        } else {
            echo "<script>alert(\"Error al subir la fotografía.\");</script>";
        }
    }


    // Verificar si el número de DUI de empleado ya existe
    $verificar_dui = "SELECT * FROM personal WHERE DUI='$DUI'";
    $resultado = mysqli_query($con, $verificar_dui);
    if (mysqli_num_rows($resultado) >= 1) {
        echo "<script>alert(\"El número de DUI de usuario ya existe.\");</script>";
    } else {
        // Insertar nuevo empleado en la base de datos
        $insertar = "INSERT INTO personal (nombre, Telefono, DUI, fechaNacimiento, departamento, distrito, coloniaResidencia, calleResidencia, casaResidencia, estadoCivil, fotografía) VALUES ('$nombre', '$teléfono', '$DUI', '$fechaNacimiento', '$departamento', '$distrito', '$coloniaResidencia', '$calleResidencia', '$casaResidencia', '$estadoCivil', '$fotografía')";
        if (mysqli_query($con, $insertar)) {
            echo "<script>alert(\"Usuario registrado exitosamente.\"); window.location.href = '../views/personal.php';</script>";
        } else {
            echo "<script>alert(\"Error al registrar el usuario: " . mysqli_error($con) . "\"); window.location.href = 'addEmployee.php';</script>";
        }
    }
    // Cerrar la conexión a la base de datos
    mysqli_close($con);
}
?>