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

// Consulta para obtener las mascotas del catálogo
$sql_mascotas = "
    SELECT m.id_mascota, m.nombre, m.descripcion, m.raza, m.tamano, m.color, m.sexo, 
    m.imagen, m.lat, m.lng, m.tipo, m.usuario_id, u.Nombre_completo AS nombre_usuario
    FROM mascotas m
    JOIN usuario u ON m.usuario_id = u.id_usuario
";
$result_mascotas = $conn->query($sql_mascotas);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PETLOVER - Catálogo</title>
    <link rel="stylesheet" href="estilos/catalogo.css">
    <style>
        /* Estilos para el botón del menú de hamburguesa */
        #btn-menu {
            display: none; /* Ocultarlo por defecto */
            font-size: 24px;
            cursor: pointer;
            color: var(--color-principal);
            margin-left: 10px;
        }

        /* Menú lateral */
        .menu-lateral {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100%;
            background-color: #333;
            color: #fff;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: 1000;
            padding-top: 60px;
        }

        .menu-lateral ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .menu-lateral ul li {
            padding: 15px;
            border-bottom: 1px solid #444;
        }

        .menu-lateral ul li a {
            color: #fff;
            text-decoration: none;
        }

        /* Mostrar menú cuando está activo */
        .menu-lateral.active {
            transform: translateX(0);
        }

        /* Catálogo de mascotas */
        .catalogo {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 50px;
        }

        .mascota {
            border: 1px solid #ccc;
            padding: 20px;
            width: 250px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .mascota img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <!-- Cabecera -->
    <header>
        <div class="contenedor">
            <div class="logo">
                <ion-icon name="ionicons ion-map"></ion-icon>
                <span>PETLOVER</span>
                <ion-icon id="btn-menu" name="menu"></ion-icon>
            </div>
            <div class="menu-opciones">
                <ul>
                    <li>
                        <a href="inicio.php">Home</a>
                    </li>
                    <li>
                        <a href="mapa_marcadores.html">Mapa de búsqueda</a>
                    </li>
                    <li>
                        <a href="catalogo.php">Busca a tu mascota</a>
                    </li>
                    <li>
                        <a href="">¿Quienes somos?</a>
                    </li>
                </ul>
            </div>
            <div class="controles-usuario">
                <div class="user-container">
                    <span class="user-name"><?php echo utf8_decode($row['Nombre_completo']); ?></span>
                    <div class="user-icon">
                        <ion-icon name="person-circle-outline" id="menu-btn"></ion-icon>
                    </div>
                    <div id="menu-dropdown" class="dropdown-content">
                        <a href="perfil.php">Perfil</a>
                        <a href="ajustes.php">Ajustes</a>
                        <a href="cerrar.php">Cerrar sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Menú lateral -->
    <nav id="menu-lateral" class="menu-lateral">
        <ul>
            <li><a href="indexfinal.html">Inicio</a></li>
            <li><a href="mapa_marcadoreslogin.html">Mapa de búsqueda</a></li>
            <li><a href="catalogo.php">Busca a tu mascota</a></li>
        </ul>
    </nav>

    <!-- Sección de catálogo -->
    <main>
        <section class="seccion-1">
            <div class="texto">
                <h1 class="titulo-principal">Catálogo de Mascotas</h1>
                <div class="catalogo">
                    <?php
                    if ($result_mascotas->num_rows > 0) {
                        while ($row_mascota = $result_mascotas->fetch_assoc()) {
                            echo '<div class="mascota">';
                            echo '<h3>' . htmlspecialchars($row_mascota["nombre"]) . '</h3>';
                            echo '<p><strong>Raza:</strong> ' . htmlspecialchars($row_mascota["raza"]) . '</p>';
                            echo '<p><strong>Tamaño:</strong> ' . htmlspecialchars($row_mascota["tamano"]) . '</p>';
                            echo '<p><strong>Color:</strong> ' . htmlspecialchars($row_mascota["color"]) . '</p>';
                            echo '<p><strong>Sexo:</strong> ' . htmlspecialchars($row_mascota["sexo"]) . '</p>';
                            echo '<p><strong>Descripción:</strong> ' . htmlspecialchars($row_mascota["descripcion"]) . '</p>';
                            echo '<p><strong>Reportado por:</strong> ' . htmlspecialchars($row_mascota["nombre_usuario"]) . '</p>';
                            if ($row_mascota["imagen"]) {
                                echo '<img src="data:image/jpeg;base64,' . htmlspecialchars($row_mascota["imagen"]) . '" alt="Imagen de ' . htmlspecialchars($row_mascota["nombre"]) . '">';
                            } else {
                                echo '<p>Sin imagen disponible</p>';
                            }
                            echo '</div>';
                        }
                    } else {
                        echo "<p>No hay mascotas registradas.</p>";
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>

    <!-- Scripts de Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="js/inicio.js"></script>
    <script>
        // JavaScript para manejar el menú de hamburguesa y desplegable
        document.addEventListener('DOMContentLoaded', () => {
            const btnMenu = document.getElementById('btn-menu');
            const menuLateral = document.getElementById('menu-lateral');
            const menuBtn = document.getElementById('menu-btn');
            const dropdown = document.getElementById('menu-dropdown');

            btnMenu.addEventListener('click', () => {
                menuLateral.classList.toggle('active');
            });

            menuBtn.addEventListener('click', () => {
                dropdown.classList.toggle('show');
            });

            // Cerrar el menú desplegable si se hace clic fuera de él
            window.addEventListener('click', (event) => {
                if (!event.target.matches('#menu-btn')) {
                    if (dropdown.classList.contains('show')) {
                        dropdown.classList.remove('show');
                    }
                }
            });
        });
    </script>
</body>

</html>

<?php
$conn->close();
?>
