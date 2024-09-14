<?php
include("conexion.php");
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header("location: FYP.html");
    exit();
}

// Obtener el correo y el ID de usuario de la sesión
$user = $_SESSION['correo'];
$id_usuario = $_SESSION['id_usuario'];

// Consulta para obtener el nombre completo del usuario
$sql = "SELECT Nombre_completo, correo FROM usuario WHERE correo='$user'";
$resultado = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$row = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>PETLOVER</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="Imagenes/iconoApp.ico">
    <link rel="stylesheet" href="estilos/inicio.css">
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="btn-menu">
                <label for="btn-menu">☰</label>
            </div>
            <div class="logo">
                <h1>PETLOVER</h1>
            </div>
            <nav class="menu">
                <a href="inicio.php">Inicio</a>
                <a href="nosotros.html">Nosotros</a>
                <a href="#">Blog</a>
                <a href="nosotros.html">Contacto</a>
                <a href="cerrar.php">Cerrar sesión</a>
                <a href="confiPersonal.php">Configuración personal</a>
            </nav>
        </div>
    </header>
    <div class="capa"></div>
    <!--    --------------->
    <input type="checkbox" id="btn-menu">
    <div class="container-menu">
        <div class="cont-menu">
            <nav>
                <div class="bienv">
                    <p style="color:orange;">Bienvenido</p>
                    <p style="color:orange;"><?php echo utf8_decode($row['Nombre_completo']); ?> </p>
                </div>
                <a href="inicio.php">Inicio</a>
                <a href="mapa_marcadores.html">Mapa</a>
                <a href="index.html">Hacer un reporte</a>
                <a href="catalogo.php">Ver reportes</a>
                <a href="bandeja_mensajes.php">Chats</a>
                <a href="IniciarSesion.php">Iniciar Sesión</a>
                <label for="btn-menu">✖️</label>
            </nav>
        </div>
    </div>
</body>

</html>
