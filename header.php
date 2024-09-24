<!-- header.php -->
<header>
    <div class="contenedor">
        <div class="logo">
            <ion-icon id="btn-menu" name="menu"></ion-icon> <!-- Mueve el menú hamburguesa al lado izquierdo -->
            <ion-icon name="ionicons ion-map"></ion-icon>
            <span>PETLOVER</span>
        </div>
        <div class="menu-opciones">
            <ul>
                <li>
                    <a href="inicio.php">Home</a>
                </li>
                <li>
                    <a href="mapa_marcadores.html">Mapa de busqueda</a>
                </li>
                <li>
                    <a href="catalogo.php">Busca a tu mascota</a>
                </li>
                <li>
                    <a href="pruebaregistromascota.html">Reporta tu mascota</a>
                </li>
                <li>
                    <a href="bandeja_mensajes.php">Mis chats</a>
                </li>
            </ul>
        </div>
        <div class="controles-usuario">
            <div class="user-container">
                <div class="user-icon">
                    <ion-icon name="person-circle-outline" id="menu-btn"></ion-icon>
                </div>
                <div id="menu-dropdown" class="dropdown-content">
                    <!-- Mostrar el nombre del usuario en el menú desplegable -->
                    <span class="dropdown-username"><?php echo $nombre_completo; ?></span>
                    <a href="perfil.php">Perfil</a>
                    <a href="ajustes.php">Ajustes</a>
                    <a href="cerrar.php">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </div>
</header>


