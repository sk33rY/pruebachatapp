<?php
include("conexion.php");
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header("location: inicio.php");
    exit();
}

// Obtener el correo y el ID de usuario de la sesión
$user = $_SESSION['correo'];
$id_usuario = $_SESSION['id_usuario'];

// Consulta para obtener el nombre completo del usuario
$sql = "SELECT Nombre_completo, correo FROM usuario WHERE correo='$user'";
$resultado = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$row = $resultado->fetch_assoc();

// Verificar si se obtuvo un resultado y manejar el caso donde $row sea null
$nombre_completo = isset($row['Nombre_completo']) ? utf8_decode($row['Nombre_completo']) : 'Usuario';
?>
