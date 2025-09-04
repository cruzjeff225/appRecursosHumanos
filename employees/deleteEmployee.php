<?php 
include_once '../config/config.php';


// Obtener el ID del usuario a eliminar desde la URL
$idPersonal = isset($_POST['idPersonal']) ? $_POST['idPersonal'] : '';
// Consulta para eliminar el usuario
$eliminar = "DELETE FROM personal WHERE idPersonal='$idPersonal'";
$ejecutar_eliminar = mysqli_query($con, $eliminar);
if ($ejecutar_eliminar) {
    header("Location: ../views/personal.php");
} else {
    echo "Error al eliminar el usuario.";
}
?>