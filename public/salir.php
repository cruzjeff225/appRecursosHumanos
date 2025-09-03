<?php
session_start();
// Destruir todas las variables de sesión y redirigir al inicio de sesión
session_destroy();
header('Location: ../views/index.php');
exit

?>;