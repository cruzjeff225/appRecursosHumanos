<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../views/index.php");
    exit;
}

include_once '../config/config.php';

// Obtener el ID del empleado
$idPersonal = $_GET['idPersonal'] ?? '';

if (!$idPersonal) {
    echo "<script>alert('ID de empleado no válido.'); window.location.href = '../views/personal.php';</script>";
    exit;
}

// Preparar consulta segura para obtener datos actuales
$stmt = $con->prepare("SELECT * FROM personal WHERE idPersonal = ?");
$stmt->bind_param("i", $idPersonal);
$stmt->execute();
$result = $stmt->get_result();
$datos = $result->fetch_assoc();
$stmt->close();

if (!$datos) {
    echo "<script>alert('Empleado no encontrado.'); window.location.href = '../views/personal.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Empleado</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<?php include_once('../views/nav.php'); ?>

<div class="container mt-5">
    <h1 class="mb-4 text-center fw-bold">Editar Empleado</h1>
    <form action="" method="POST" class="form-control shadow-sm p-4" enctype="multipart/form-data">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="nombre" class="form-label fw-bold">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre"
                       value="<?= htmlspecialchars($datos['nombre']) ?>"
                       pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ ]{2,50}$"
                       title="Solo letras y espacios (2-50 caracteres)" required>
            </div>
            <div class="col-md-6">
                <label for="Telefono" class="form-label fw-bold">Teléfono</label>
                <input type="text" class="form-control" id="Telefono" name="Telefono"
                       value="<?= htmlspecialchars($datos['Telefono']) ?>"
                       pattern="^[267][0-9]{7}$"
                       title="Debe tener 8 dígitos, iniciando con 2, 6 o 7" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="DUI" class="form-label fw-bold">DUI</label>
                <input type="text" class="form-control" id="DUI" name="DUI"
                       value="<?= htmlspecialchars($datos['DUI']) ?>"
                       pattern="^[0-9]{8}-?[0-9]$"
                       title="Formato válido: 12345678-9 o 123456789" required>
            </div>
            <div class="col-md-6">
                <label for="fechaNacimiento" class="form-label fw-bold">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento"
                       value="<?= htmlspecialchars($datos['fechaNacimiento']) ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="departamento" class="form-label fw-bold">Departamento</label>
                <select id="departamento" class="form-select" name="departamento" required>
                    <option selected><?= htmlspecialchars($datos['departamento']) ?></option>
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
                       value="<?= htmlspecialchars($datos['distrito']) ?>"
                       pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ ]{2,50}$"
                       title="Solo letras y espacios" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="coloniaResidencia" class="form-label fw-bold">Colonia</label>
                <input type="text" class="form-control" id="coloniaResidencia" name="coloniaResidencia"
                       value="<?= htmlspecialchars($datos['coloniaResidencia']) ?>"
                       pattern="^[A-Za-z0-9ÁÉÍÓÚáéíóúÑñ .#-]{2,100}$"
                       title="Letras, números y símbolos . # -" required>
            </div>
            <div class="col-md-4">
                <label for="calleResidencia" class="form-label fw-bold">Calle</label>
                <input type="text" class="form-control" id="calleResidencia" name="calleResidencia"
                       value="<?= htmlspecialchars($datos['calleResidencia']) ?>"
                       pattern="^[A-Za-z0-9ÁÉÍÓÚáéíóúÑñ .#-]{2,100}$"
                       title="Letras, números y símbolos . # -" required>
            </div>
            <div class="col-md-4">
                <label for="casaResidencia" class="form-label fw-bold">Casa</label>
                <input type="text" class="form-control" id="casaResidencia" name="casaResidencia"
                       value="<?= htmlspecialchars($datos['casaResidencia']) ?>"
                       pattern="^[A-Za-z0-9ÁÉÍÓÚáéíóúÑñ .#-]{1,50}$"
                       title="Letras, números o guiones" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="estadoCivil" class="form-label fw-bold">Estado Civil</label>
                <select id="estadoCivil" class="form-select" name="estadoCivil" required>
                    <option selected><?= htmlspecialchars($datos['estadoCivil']) ?></option>
                    <option>Soltero</option><option>Casado</option>
                    <option>Divorciado</option><option>Viudo</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="fotografía" class="form-label fw-bold">Fotografía</label>
                <input class="form-control" id="fotografía" type="file" name="fotografía" accept="image/*">
                <?php if (!empty($datos['fotografía'])): ?>
                    <small class="text-muted">Imagen actual: <?= htmlspecialchars($datos['fotografía']) ?></small>
                <?php endif; ?>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> Guardar Cambios</button>
        </div>
    </form>
</div>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura segura de datos
    $nombre = $_POST['nombre'];
    $telefono = $_POST['Telefono'];
    $DUI = $_POST['DUI'];
    $fechaNacimiento = $_POST['fechaNacimiento'];
    $departamento = $_POST['departamento'];
    $distrito = $_POST['distrito'];
    $coloniaResidencia = $_POST['coloniaResidencia'];
    $calleResidencia = $_POST['calleResidencia'];
    $casaResidencia = $_POST['casaResidencia'];
    $estadoCivil = $_POST['estadoCivil'];

    // Manejo de fotografía
    $fotografia = $datos['fotografía'];
    if (isset($_FILES['fotografía']) && $_FILES['fotografía']['error'] !== UPLOAD_ERR_NO_FILE) {
        $dir = "../img/imgEmployees/";
        $fileTmp = $_FILES['fotografía']['tmp_name'];
        $fileName = basename($_FILES['fotografía']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $fileSize = $_FILES['fotografía']['size'];
        $fileType = mime_content_type($fileTmp);
        $validExt = ['jpg','jpeg','png','gif','webp'];
        $validType = ['image/jpeg','image/png','image/gif','image/webp'];
        $maxSize = 2*1024*1024;

        if (!in_array($fileExt, $validExt) || !in_array($fileType, $validType) || $fileSize > $maxSize || !getimagesize($fileTmp)) {
            echo "<script>alert('Imagen inválida o demasiado grande (máx 2MB)'); window.history.back();</script>";
            exit;
        }

        $filePath = $dir . $fileName;
        if (!move_uploaded_file($fileTmp, $filePath)) {
            echo "<script>alert('Error al subir la imagen'); window.history.back();</script>";
            exit;
        }
        $fotografia = $fileName;
    }

    // Verificar DUI único
    $stmt = $con->prepare("SELECT idPersonal FROM personal WHERE DUI=? AND idPersonal!=?");
    $stmt->bind_param("si", $DUI, $idPersonal);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "<script>alert('El DUI ya está en uso'); window.location.href='editEmployee.php?idPersonal=$idPersonal';</script>";
        exit;
    }
    $stmt->close();

    // Actualizar empleado con prepared statement
    $stmt = $con->prepare("UPDATE personal SET nombre=?, Telefono=?, DUI=?, fechaNacimiento=?, departamento=?, distrito=?, coloniaResidencia=?, calleResidencia=?, casaResidencia=?, estadoCivil=?, fotografía=? WHERE idPersonal=?");
    $stmt->bind_param("sssssssssssi", $nombre, $telefono, $DUI, $fechaNacimiento, $departamento, $distrito, $coloniaResidencia, $calleResidencia, $casaResidencia, $estadoCivil, $fotografia, $idPersonal);
    
    if ($stmt->execute()) {
        echo "<script>alert('Empleado actualizado exitosamente'); window.location.href='../views/personal.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar: ".$stmt->error."'); window.history.back();</script>";
    }
    $stmt->close();
    $con->close();
}
?>
