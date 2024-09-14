<?php
// Inicializar la sesión.
 session_start();
// Destruir todas las variables de sesión.
 session_destroy();
// Volver al inicio
echo "<script>alert('cerró sesión');</script>";
 require('inicio.php');

?>