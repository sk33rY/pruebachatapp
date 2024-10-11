<?php
include("conexion.php");
// Verificar si la sesión ya está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("location: iniciose.html");
    exit();
}

// Obtener el nombre del usuario logueado
$user = $_SESSION['correo'];
$sql = "SELECT Nombre_completo FROM usuario WHERE correo='$user'";
$resultado = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$row = $resultado->fetch_assoc();
$nombre_completo = isset($row['Nombre_completo']) ? utf8_decode($row['Nombre_completo']) : 'Usuario';
?>
<link rel="stylesheet" href="estilos/header_login.css">
<header>
    <div class="contenedor">
        <div class="logo">
            <ion-icon name="ionicons ion-map"></ion-icon>
            <span>PETLOVER</span>
        </div>

        <!-- Menú de hamburguesa para móviles -->
        <ion-icon id="btn-menu" name="menu-outline"></ion-icon>

        <div class="menu-opciones">
            <ul>
                <li><a href="inicio.php">Home</a></li>
                <li><a href="mapa_marcadoreslogin.php">Mapa de búsqueda</a></li>
                <li><a href="catalogo.php">Busca a tu mascota</a></li>
                <li><a href="registro_mascota.php">Reporta tu mascota</a></li>
                <li><a href="bandeja_mensajes.php">Mis chats</a></li>
            </ul>
        </div>

        <div class="controles-usuario">
            <div class="user-container">
                <ion-icon name="person-circle-outline" id="menu-btn"></ion-icon>
                <div id="menu-dropdown" class="dropdown-content">
                    <a href="perfil.php">Perfil</a>
                    <a href="ajustes.php">Ajustes</a>
                    <a href="cerrar.php">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Menú lateral para móviles -->
<nav id="menu-lateral" class="menu-lateral">
    <ul>
        <li><a href="inicio.php">Home</a></li>
        <li><a href="mapa_marcadoreslogin.php">Mapa de búsqueda</a></li>
        <li><a href="catalogo.php">Busca a tu mascota</a></li>
        <li><a href="pruebaregistromascota.html">Reporta tu mascota</a></li>
        <li><a href="bandeja_mensajes.php">Mis chats</a></li>
    </ul>
</nav>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const btnMenu = document.getElementById('btn-menu');
    const menuLateral = document.getElementById('menu-lateral');
    const menuBtn = document.getElementById('menu-btn');
    const dropdown = document.getElementById('menu-dropdown');
    const body = document.querySelector('body');

    // Manejar el menú de hamburguesa
    btnMenu.addEventListener('click', () => {
        menuLateral.classList.toggle('active');
        body.classList.toggle('menu-active');
    });

    // Cerrar el menú lateral si se hace clic fuera de él
    window.addEventListener('click', (event) => {
        if (!menuLateral.contains(event.target) && !btnMenu.contains(event.target)) {
            menuLateral.classList.remove('active');
            body.classList.remove('menu-active');
        }
    });

    // Manejar el menú desplegable del usuario
    menuBtn.addEventListener('click', (event) => {
        event.stopPropagation();
        dropdown.classList.toggle('show');
    });

    // Cerrar el menú desplegable si se hace clic fuera de él
    window.addEventListener('click', (event) => {
        if (!menuBtn.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    });
});
</script>
