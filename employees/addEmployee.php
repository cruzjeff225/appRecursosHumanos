<?php
// Endpoint para agregar empleados, retornará JSON
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

include_once '../config/config.php';

// Solo acepta método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no válido']);
    exit;
}

//Helper para enviar errores
function send_error($msg, $code = 400)
{
    http_response_code($code);
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

// Capturando datos del formulario
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$telefono = isset($_POST['Telefono']) ? trim($_POST['Telefono']) : '';
$DUI = isset($_POST['DUI']) ? trim($_POST['DUI']) : '';
$fechaNacimiento = isset($_POST['fechaNacimiento']) ? trim($_POST['fechaNacimiento']) : '';
$departamento = isset($_POST['departamento']) ? trim($_POST['departamento']) : '';
$distrito = isset($_POST['distrito']) ? trim($_POST['distrito']) : '';
$coloniaResidencia = isset($_POST['coloniaResidencia']) ? trim($_POST['coloniaResidencia']) : '';
$calleResidencia = isset($_POST['calleResidencia']) ? trim($_POST['calleResidencia']) : '';
$casaResidencia = isset($_POST['casaResidencia']) ? trim($_POST['casaResidencia']) : '';
$estadoCivil = isset($_POST['estadoCivil']) ? trim($_POST['estadoCivil']) : '';

//Validaciones básicas
if (empty($nombre) || empty($telefono) || empty($DUI) || empty($fechaNacimiento) || empty($departamento) || empty($distrito) || empty($coloniaResidencia) || empty($calleResidencia) || empty($casaResidencia) || empty($estadoCivil)) {
    send_error('Campos requeridos faltantes.');
}

// Foto por defecto
$fotografia = 'user.png';

// Manejo de la carga de la fotografía
$fileSelected = isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] !== UPLOAD_ERR_NO_FILE;

if ($fileSelected) {
    if ($_FILES['fotografia']['error'] !== UPLOAD_ERR_OK) {
        send_error('Ocurrió un error al subir el archivo.');
    }

    $directorio = __DIR__ . "/../img/imgEmployees/";
    if (!is_dir($directorio)) {
        if (!mkdir($directorio, 0755, true)) {
            send_error('No se pudo crear el directorio de imágenes en el servidor.', 500);
        }
    }

    $tempName = $_FILES['fotografia']['tmp_name'];
    $originalName = basename($_FILES['fotografia']['name']);
    $fileSize = $_FILES['fotografia']['size'];
    $fileType = mime_content_type($tempName);
    $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    $extensionesValidas = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $tiposValidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $tamanoValido = 2 * 1024 * 1024;

    if (!in_array($fileExtension, $extensionesValidas)) {
        send_error('La imagen seleccionada tiene una extensión inválida.');
    }
    if (!in_array($fileType, $tiposValidos)) {
        send_error('La imagen seleccionada tiene un tipo inválido.');
    }
    if ($fileSize > $tamanoValido) {
        send_error('La imagen no debe ser mayor a 2MB.');
    }
    if (!getimagesize($tempName)) {
        send_error('El archivo no es una imagen válida.');
    }

    // Mover el archivo al directorio destino con un nombre único
    $newFileName = uniqid('emp_', true) . '.' . $fileExtension;
    $destination = $directorio . $newFileName;
    if (!move_uploaded_file($tempName, $destination)) {
        send_error('Error al mover la fotografía en el servidor.', 500);
    }
    $fotografia = $newFileName;
}

// Verificar DUI único
$verificar_dui = "SELECT idPersonal FROM personal WHERE DUI = '" . mysqli_real_escape_string($con, $DUI) . "' LIMIT 1";
$resultado = mysqli_query($con, $verificar_dui);
if ($resultado === false) {
    send_error('Error en la consulta de verificación: ' . mysqli_error($con), 500);
}
if (mysqli_num_rows($resultado) >= 1) {
    send_error('El número de DUI de usuario ya existe.', 409);
}

// Insertar nuevo empleado en la BD haciendo uso de valores escapados
$nombre_e = mysqli_real_escape_string($con, $nombre);
$telefono_e = mysqli_real_escape_string($con, $telefono);
$DUI_e = mysqli_real_escape_string($con, $DUI);
$fechaNacimiento_e = mysqli_real_escape_string($con, $fechaNacimiento);
$departamento_e = mysqli_real_escape_string($con, $departamento);
$distrito_e = mysqli_real_escape_string($con, $distrito);
$coloniaResidencia_e = mysqli_real_escape_string($con, $coloniaResidencia);
$calleResidencia_e = mysqli_real_escape_string($con, $calleResidencia);
$casaResidencia_e = mysqli_real_escape_string($con, $casaResidencia);
$estadoCivil_e = mysqli_real_escape_string($con, $estadoCivil);
$fotografia_e = mysqli_real_escape_string($con, $fotografia);

$insertar = "INSERT INTO personal (nombre, Telefono, DUI, fechaNacimiento, departamento, distrito, coloniaResidencia, calleResidencia, casaResidencia, estadoCivil, fotografía) VALUES ('{$nombre_e}', '{$telefono_e}', '{$DUI_e}', '{$fechaNacimiento_e}', '{$departamento_e}', '{$distrito_e}', '{$coloniaResidencia_e}', '{$calleResidencia_e}', '{$casaResidencia_e}', '{$estadoCivil_e}', '{$fotografia_e}')";

if (!mysqli_query($con, $insertar)) {
    send_error('Error al registrar el usuario: ' . mysqli_error($con), 500);
}

$newId = mysqli_insert_id($con);

// Retornar datos del nuevo empleado para renderizar
http_response_code(201);
echo json_encode([
    'success' => true,
    'message' => 'Usuario registrado exitosamente.',
    'data' => [
        'idPersonal' => $newId,
        'nombre' => $nombre_e,
        'Telefono' => $telefono_e,
        'DUI' => $DUI_e,
        'fechaNacimiento' => $fechaNacimiento_e,
        'departamento' => $departamento_e,
        'distrito' => $distrito_e,
        'coloniaResidencia' => $coloniaResidencia_e,
        'calleResidencia' => $calleResidencia_e,
        'casaResidencia' => $casaResidencia_e,
        'estadoCivil' => $estadoCivil_e,
        'fotografia' => $fotografia_e,
    ]
]);

mysqli_close($con);

?>