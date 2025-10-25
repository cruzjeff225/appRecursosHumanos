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

// Obtener idUsuario
$idUsuario = isset($_POST['idUsuario']) ? (int)$_POST['idUsuario'] : 0;
if ($idUsuario <= 0) json_error('ID de usuario inválido');

// Capturando datos del formulario
$nombreUsuario = isset($_POST['nombreUsuario']) ? trim($_POST['nombreUsuario']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';

// Validaciones básicas
if ($nombreUsuario === '' || $email === '') {
    json_error('Campos requeridos faltantes.');
}

// Verificar si el correo ya existe en otro usuario
$stmt = $con->prepare("SELECT idUsuario FROM usuarios WHERE email = ? AND idUsuario != ?");
if (!$stmt) json_error('Error en la consulta de verificación de email', 500);
$stmt->bind_param("si", $email, $idUsuario);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    json_error('El correo electrónico ya está en uso por otro usuario');
}
$stmt->close();

// Preparar y ejecutar la actualización
$stmt = $con->prepare("UPDATE usuarios SET nombreUsuario = ?, email = ? WHERE idUsuario = ?");
if (!$stmt) {
    json_error('Error en la preparación de la actualización', 500);
}
$stmt->bind_param("ssi", $nombreUsuario, $email, $idUsuario);
if ($stmt->execute()) {
    // Devolver también los datos actualizados para que el frontend pueda actualizar la fila sin recargar
    $resultData = [
        'idUsuario' => $idUsuario,
        'nombreUsuario' => $nombreUsuario,
        'email' => $email
    ];
    echo json_encode(['success' => true, 'message' => 'Datos actualizados exitosamente.', 'data' => $resultData]);
} else {
    json_error('Error al actualizar los datos: ' . $stmt->error, 500);
}
$stmt->close();
$con->close();

?>