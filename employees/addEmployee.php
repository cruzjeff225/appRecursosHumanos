<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../views/index.php");
    exit;
}

include_once '../config/config.php';

// Variables del formulario (se inicializan vacías)
$nombre = $_POST['nombre'] ?? '';
$telefono = $_POST['Teléfono'] ?? '';
$DUI = $_POST['DUI'] ?? '';
$fechaNacimiento = $_POST['fechaNacimiento'] ?? '';
$departamento = $_POST['departamento'] ?? '';
$distrito = $_POST['distrito'] ?? '';
$coloniaResidencia = $_POST['coloniaResidencia'] ?? '';
$calleResidencia = $_POST['calleResidencia'] ?? '';
$casaResidencia = $_POST['casaResidencia'] ?? '';
$estadoCivil = $_POST['estadoCivil'] ?? '';
$fotografia = $_POST['fotografía'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<?php include_once('../views/nav.php'); ?>

<div class="container mt-5">
    <h1 class="mb-4 text-center fw-bold">Registro de Empleado</h1>
    <form action="" method="POST" class="form-control shadow-sm p-4" enctype="multipart/form-data">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="nombre" class="form-label fw-bold">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre"
                       placeholder="Ingresa el nombre del empleado"
                       pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ ]{2,50}$"
                       title="Solo letras y espacios (2-50 caracteres)" required>
            </div>
            <div class="col-md-6">
                <label for="Teléfono" class="form-label fw-bold">Teléfono</label>
                <input type="text" class="form-control" id="Teléfono" name="Teléfono"
                       placeholder="Ingresa el teléfono"
                       pattern="^[267][0-9]{7}$"
                       title="Debe tener 8 dígitos, iniciando con 2, 6 o 7" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="DUI" class="form-label fw-bold">DUI</label>
                <input type="text" class="form-control" id="DUI" name="DUI"
                       placeholder="Ingresa el DUI"
                       pattern="^[0-9]{8}-?[0-9]$"
                       title="Formato válido: 12345678-9 o 123456789" required>
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
                    <option>Ahuachapán</option><option>Cabañas</option><option>Chalatenango</option>
                    <option>Cuscatlán</option><option>La Libertad</option><option>La Paz</option>
                    <option>La Unión</option><option>Morazán</option><option>San Miguel</option>
                    <option>San Vicente</option><option>San Salvador</option><option>Santa Ana</option>
                    <option>Sonsonate</option><option>Usulután</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="distrito" class="form-label fw-bold">Distrito</label>
                <input type="text" class="form-control" id="distrito" name="distrito"
                       placeholder="Ingresa el distrito"
                       pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ ]{2,50}$"
                       title="Solo letras y espacios" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="coloniaResidencia" class="form-label fw-bold">Colonia</label>
                <input type="text" class="form-control" id="coloniaResidencia" name="coloniaResidencia"
                       placeholder="Ingresa la colonia"
                       pattern="^[A-Za-z0-9ÁÉÍÓÚáéíóúÑñ .#-]{2,100}$"
                       title="Letras, números y símbolos . # -" required>
            </div>
            <div class="col-md-4">
                <label for="calleResidencia" class="form-label fw-bold">Calle</label>
                <input type="text" class="form-control" id="calleResidencia" name="calleResidencia"
                       placeholder="Ingresa la calle"
                       pattern="^[A-Za-z0-9ÁÉÍÓÚáéíóúÑñ .#-]{2,100}$"
                       title="Letras, números y símbolos . # -" required>
            </div>
            <div class="col-md-4">
                <label for="casaResidencia" class="form-label fw-bold">Casa</label>
                <input type="text" class="form-control" id="casaResidencia" name="casaResidencia"
                       placeholder="Número o referencia de casa"
                       pattern="^[A-Za-z0-9ÁÉÍÓÚáéíóúÑñ .#-]{1,50}$"
                       title="Letras, números o guiones" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="estadoCivil" class="form-label fw-bold">Estado Civil</label>
                <select id="estadoCivil" class="form-select" name="estadoCivil" required>
                    <option selected disabled>Seleccionar...</option>
                    <option>Soltero</option><option>Casado</option>
                    <option>Divorciado</option><option>Viudo</option>
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
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Valor por defecto si no se sube imagen
    $fotografia = 'user.png';

    if (isset($_FILES['fotografía']) && $_FILES['fotografía']['error'] !== UPLOAD_ERR_NO_FILE) {
        $directorio = "../img/imgEmployees/";
        $tempName = $_FILES['fotografía']['tmp_name'];
        $fileName = basename($_FILES['fotografía']['name']);
        $fileSize = $_FILES['fotografía']['size'];
        $fileType = mime_content_type($tempName);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $path = $directorio . $fileName;

        $extensionesValidas = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $tiposValidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $tamanoValido = 2 * 1024 * 1024;

        if (!in_array($fileExtension, $extensionesValidas) ||
            !in_array($fileType, $tiposValidos) ||
            $fileSize > $tamanoValido ||
            !getimagesize($tempName)) {
            echo "<script>alert('Imagen inválida o demasiado grande (máx 2MB).'); window.history.back();</script>";
            exit;
        }

        if (!move_uploaded_file($tempName, $path)) {
            echo "<script>alert('Error al subir la fotografía.'); window.history.back();</script>";
            exit;
        }

        $fotografia = $fileName;
    }

    // Verificar DUI único
    $verificar_dui = mysqli_query($con, "SELECT * FROM personal WHERE DUI='$DUI'");
    if (mysqli_num_rows($verificar_dui) >= 1) {
        echo "<script>alert('El número de DUI ya existe.'); window.history.back();</script>";
        exit;
    }

    // Insertar empleado
    $insertar = "INSERT INTO personal 
        (nombre, Telefono, DUI, fechaNacimiento, departamento, distrito, coloniaResidencia, calleResidencia, casaResidencia, estadoCivil, fotografía) 
        VALUES 
        ('$nombre', '$telefono', '$DUI', '$fechaNacimiento', '$departamento', '$distrito', '$coloniaResidencia', '$calleResidencia', '$casaResidencia', '$estadoCivil', '$fotografia')";

    if (mysqli_query($con, $insertar)) {
        echo "<script>alert('Usuario registrado exitosamente.'); window.location.href = '../views/personal.php';</script>";
    } else {
        echo "<script>alert('Error al registrar usuario: ".mysqli_error($con)."'); window.history.back();</script>";
    }

    mysqli_close($con);
}
?>
