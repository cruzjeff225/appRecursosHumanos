<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

require_once '../config/config.php';

// Solo acepta método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no válido']);
    exit;
}

//Helper para enviar errores
function json_error($msg, $code = 400) {
    http_response_code($code);
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

// Obtener idPersonal
$idPersonal = isset($_POST['idPersonal']) ? (int)$_POST['idPersonal'] : 0;
if ($idPersonal <= 0) json_error('ID de empleado inválido');

// Capturando datos del formulario
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$Telefono = isset($_POST['Telefono']) ? trim($_POST['Telefono']) : '';
$DUI = isset($_POST['DUI']) ? trim($_POST['DUI']) : '';
$fechaNacimiento = isset($_POST['fechaNacimiento']) ? trim($_POST['fechaNacimiento']) : '';
$departamento = isset($_POST['departamento']) ? trim($_POST['departamento']) : '';
$distrito = isset($_POST['distrito']) ? trim($_POST['distrito']) : '';
$coloniaResidencia = isset($_POST['coloniaResidencia']) ? trim($_POST['coloniaResidencia']) : '';
$calleResidencia = isset($_POST['calleResidencia']) ? trim($_POST['calleResidencia']) : '';
$casaResidencia = isset($_POST['casaResidencia']) ? trim($_POST['casaResidencia']) : '';
$estadoCivil = isset($_POST['estadoCivil']) ? trim($_POST['estadoCivil']) : '';

// Validaciones básicas
if ($nombre === '' || $Telefono === '' || $DUI === '' || $fechaNacimiento === '' || $departamento === '' || $distrito === '' || $coloniaResidencia === '' || $calleResidencia === '' || $casaResidencia === '' || $estadoCivil === '') {
    json_error('Campos requeridos faltantes.');
}

// Verificar si el DUI ya existe en otro empleado
$stmt = $con->prepare("SELECT idPersonal FROM personal WHERE DUI = ? AND idPersonal != ?");
if (!$stmt) json_error('Error en la consulta de verificación de DUI', 500);
$stmt->bind_param("si", $DUI, $idPersonal);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    json_error('El DUI ya está en uso por otro empleado');
}
$stmt->close();

// Manejo de la carga de la fotografía
$fileField = null;
if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] !== UPLOAD_ERR_NO_FILE) $fileField = 'fotografia';
elseif (isset($_FILES['fotografía']) && $_FILES['fotografía']['error'] !== UPLOAD_ERR_NO_FILE) $fileField = 'fotografía';

$fotografiaNombre = null;
// Si se seleccionó un archivo, validar y mover
if ($fileField) {
    $file = $_FILES[$fileField];
    if ($file['error'] !== UPLOAD_ERR_OK) json_error('Error al subir la imagen');

    $fileTmp = $file['tmp_name'];
    $fileName = basename($file['name']);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $fileSize = $file['size'];
    $fileInfo = @getimagesize($fileTmp);
    $fileType = $fileInfo['mime'] ?? mime_content_type($fileTmp);

    $validExt = ['jpg','jpeg','png','gif','webp'];
    $validType = ['image/jpeg','image/png','image/gif','image/webp'];
    $maxSize = 2 * 1024 * 1024; // 2MB

    if (!in_array($fileExt, $validExt) || !in_array($fileType, $validType) || $fileSize > $maxSize || !$fileInfo) {
        json_error('Imagen inválida o demasiado grande (máx 2MB)');
    }

    // Generar nombre unico y mover
    $newFileName = uniqid('emp_', true) . '.' . $fileExt;
    $destination = __DIR__ . "/../img/imgEmployees/" . $newFileName;
    if (!move_uploaded_file($fileTmp, $destination)) {
        json_error('Error al mover la fotografía en el servidor.', 500);
    }
    $fotografiaNombre = $newFileName;

    // Borrar foto anterior si existe y no es por defecto
    $stmt = $con->prepare("SELECT fotografía FROM personal WHERE idPersonal = ?");
    $stmt->bind_param("i", $idPersonal);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $old = $res['fotografía'] ?? null;
    if ($old && $old !== 'user.png') {
        $oldPath = __DIR__ . '/../img/imgEmployees/' . $old;
        if (file_exists($oldPath)) @unlink($oldPath);
    }
}

// Preparar y ejecutar actualización 
if ($fotografiaNombre !== null) {
    $sql = "UPDATE personal SET nombre=?, Telefono=?, DUI=?, fechaNacimiento=?, departamento=?, distrito=?, coloniaResidencia=?, calleResidencia=?, casaResidencia=?, estadoCivil=?, fotografía=? WHERE idPersonal=?";
    $stmt = $con->prepare($sql);
    if (!$stmt) json_error('Error al preparar la actualización', 500);
    $stmt->bind_param("sssssssssssi", $nombre, $Telefono, $DUI, $fechaNacimiento, $departamento, $distrito, $coloniaResidencia, $calleResidencia, $casaResidencia, $estadoCivil, $fotografiaNombre, $idPersonal);
} else {
    $sql = "UPDATE personal SET nombre=?, Telefono=?, DUI=?, fechaNacimiento=?, departamento=?, distrito=?, coloniaResidencia=?, calleResidencia=?, casaResidencia=?, estadoCivil=? WHERE idPersonal=?";
    $stmt = $con->prepare($sql);
    if (!$stmt) json_error('Error al preparar la actualización', 500);
    $stmt->bind_param("ssssssssssi", $nombre, $Telefono, $DUI, $fechaNacimiento, $departamento, $distrito, $coloniaResidencia, $calleResidencia, $casaResidencia, $estadoCivil, $idPersonal);
}

if (!$stmt->execute()) {
    $err = $stmt->error;
    $stmt->close();
    json_error('Error al actualizar: ' . $err, 500);
}
$stmt->close();

// Recuperar registro actualizado
$stmt = $con->prepare("SELECT idPersonal, nombre, Telefono, DUI, fechaNacimiento, departamento, distrito, coloniaResidencia, calleResidencia, casaResidencia, estadoCivil, fotografía, fechaRegistro FROM personal WHERE idPersonal = ?");
$stmt->bind_param("i", $idPersonal);
$stmt->execute();
$updated = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$updated) json_error('No se pudo recuperar el empleado actualizado', 500);

// Mapear fotografía
$updated['fotografia'] = isset($updated['fotografía']) ? $updated['fotografía'] : null;
if (isset($updated['fotografía'])) unset($updated['fotografía']);

// Responder con éxito
echo json_encode(['success' => true, 'message' => 'Empleado actualizado', 'data' => $updated]);
