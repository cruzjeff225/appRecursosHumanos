<?php
include_once '../config/config.php';

$condicion = "";
if (!empty($_POST['departamento'])) {
    $departamento = mysqli_real_escape_string($con, $_POST['departamento']);
    $condicion = "WHERE departamento = '$departamento'";
}

$query = "SELECT departamento, COUNT(*) AS total FROM personal $condicion GROUP BY departamento";
$resultado = mysqli_query($con, $query);

$categorias = [];
$valores = [];

while ($fila = mysqli_fetch_assoc($resultado)) {
    $categorias[] = $fila['departamento'];
    $valores[] = (int)$fila['total'];
}

header('Content-Type: application/json');
echo json_encode(["categorias" => $categorias, "valores" => $valores]);
