<?php
//Endpoint para agregar usuarios, retornará JSON
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}
include_once '../config/config.php';

//Solo acepta método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => "Método no válido"]);
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
$nombreUsuario = isset($_POST['nombreUsuario']) ? trim($_POST['nombreUsuario']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$rol = isset($_POST['rolId']) ? trim($_POST['rolId']) : '';

//Validaciones básicas
if (empty($nombreUsuario) || empty($email) || empty($password) || empty($rol)) {
    send_error('Campos requeridos faltantes: nombreUsuario, email, password, rol');
}

//Hashear contraseña usando MD5
$passwordHash = md5($password);

//Verificar si el nombre de usuario o email ya existe
$nombreUsuario_check = mysqli_real_escape_string($con, $nombreUsuario);
$email_check = mysqli_real_escape_string($con, $email);
$verificar_usuario = "SELECT * FROM usuarios WHERE nombreUsuario = '$nombreUsuario_check' OR email = '$email_check' LIMIT 1";
$resultado = mysqli_query($con, $verificar_usuario);
if ($resultado && mysqli_num_rows($resultado) >= 1) {
    send_error('El nombre de usuario o email ya existe. Prueba con otro.', 409);
}

// Validar rol: debe ser entero
if (!ctype_digit($rol)) {
    send_error('Valor de rol inválido. Debe ser un número entero.', 400);
}
$rol_int = (int)$rol;

// Valores escapados para evitar inyecciones SQL
$nombreUsuario_scp = mysqli_real_escape_string($con, $nombreUsuario);
$email_scp = mysqli_real_escape_string($con, $email);
$passwordHash_scp = mysqli_real_escape_string($con, $passwordHash);

//Insertar nuevo usuario en la BD haciendo uso de los valores escapados
$insertar = "INSERT INTO usuarios (nombreUsuario, email, password, RolId) VALUES ('$nombreUsuario_scp', '$email_scp', '$passwordHash_scp', $rol_int)";

if (!mysqli_query($con, $insertar)) {
    send_error('Error al registrar el usuario. Consulte los logs del servidor.', 500);
}

// obtener id insertado
$inserted_id = mysqli_insert_id($con);

// Retornar datos de usuario para renderizar
http_response_code(201);
echo json_encode([
    'success' => true,
    'message' => 'Usuario registrado exitosamente.',
    'data' => [
        'idUsuario' => $inserted_id,
        'nombreUsuario' => $nombreUsuario_scp,
        'email' => $email_scp,
        'rolId' => $rol_int,
        'password' => ''
    ]
]);

mysqli_close($con);

?>