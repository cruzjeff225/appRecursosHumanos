<?php 
include_once '../config/config.php';


// Obtener el ID del usuario a eliminar desde la URL
$idUsuario = isset($_POST['idUsuario']) ? $_POST['idUsuario'] : '';
// Consulta para eliminar el usuario
$eliminar = "DELETE FROM usuarios WHERE idUsuario='$idUsuario'";
$ejecutar_eliminar = mysqli_query($con, $eliminar);
if ($ejecutar_eliminar) {
    header("Location: ../views/usuarios.php");
} else {
    echo "Error al eliminar el usuario.";
}
?>