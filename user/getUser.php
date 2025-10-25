<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

include_once '../config/config.php';

// Solo acepta método GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(401);
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

// Capturando ID del usuario
$idUsuario = isset($_GET['idUsuario']) ? (int)$_GET['idUsuario'] : 0;
if ($idUsuario <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID de usuario inválido']);
    exit;
}

// Preparar consulta segura para obtener datos actuales
$stmt = $con->prepare("SELECT * FROM usuarios WHERE idUsuario = ?");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error en la consulta']);
    exit;
}
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
    exit;
}

// Responder exitosamente con los datos
echo json_encode(['success' => true, 'data' => $data]);

?>