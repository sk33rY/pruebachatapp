<?php
include("conexion.php");
session_start();

if (isset($_SESSION['id_usuario'])) {
    include("header_login.php");
} else {
    include("header.php");
}

// Verifica si se ha recibido un mascota_id
$mascota_id = isset($_GET['mascota_id']) ? $_GET['mascota_id'] : null;

if ($mascota_id) {
    // Preparar la consulta para un caso específico
    $sql = $conn->prepare("
        SELECT m.id_mascota, m.nombre, m.descripcion, m.raza, m.tamano, m.color, m.sexo, 
        m.imagen, m.lat, m.lng, m.tipo, m.usuario_id, u.Nombre_completo AS nombre_usuario
        FROM mascotas m
        JOIN usuario u ON m.usuario_id = u.id_usuario
        WHERE m.id_mascota = ?
    ");
    $sql->bind_param("i", $mascota_id); // 'i' para integer
} else {
    // Preparar la consulta para todos los registros
    $sql = $conn->prepare("
        SELECT m.id_mascota, m.nombre, m.descripcion, m.raza, m.tamano, m.color, m.sexo, 
        m.imagen, m.lat, m.lng, m.tipo, m.usuario_id, u.Nombre_completo AS nombre_usuario
        FROM mascotas m
        JOIN usuario u ON m.usuario_id = u.id_usuario
    ");
}

$sql->execute();
$result = $sql->get_result();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PETLOVER - Catálogo</title>
    <link rel="stylesheet" href="estilos/catalogo2.css">
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
            margin-top: 10px;
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
        .titulo-principal {
    text-align: center;
}

    </style>
</head>

<body>
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
                <h1 class="titulo-principal">Mascotas reportadas</h1>
                <div class="catalogo">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row_mascota = $result->fetch_assoc()) {
                            echo '<div class="mascota">';
                            echo '<h3>' . htmlspecialchars($row_mascota["nombre"]) . '</h3>';
                            echo '<p><strong>Raza:</strong> ' . htmlspecialchars($row_mascota["raza"]) . '</p>';
                            echo '<p><strong>Tamaño:</strong> ' . htmlspecialchars($row_mascota["tamano"]) . '</p>';
                            echo '<p><strong>Color:</strong> ' . htmlspecialchars($row_mascota["color"]) . '</p>';
                            echo '<p><strong>Sexo:</strong> ' . htmlspecialchars($row_mascota["sexo"]) . '</p>';
                            echo '<p><strong>Descripción:</strong> ' . htmlspecialchars($row_mascota["descripcion"]) . '</p>';
                            echo '<p><strong>Estado:</strong> ' . $row_mascota["tipo"] . '</p>';
                            echo '<p><strong>Reportado por:</strong> ' . htmlspecialchars($row_mascota["nombre_usuario"]) . '</p>';
                            if ($row_mascota["imagen"]) {
                                echo '<img src="data:image/jpeg;base64,' . htmlspecialchars($row_mascota["imagen"]) . '" alt="Imagen de ' . htmlspecialchars($row_mascota["nombre"]) . '">';
                            } else {
                                echo '<p>Sin imagen disponible</p>';
                            }
                             // Botón de buscar coincidencias
                            echo '<form action="buscar_coincidencias.php" method="post" style="margin-bottom: 15px;">';
                            echo '<input type="hidden" name="id_mascota" value="' . htmlspecialchars($row_mascota["id_mascota"]) . '">';
                            echo '<button type="submit" class="btn btn-primary mt-3">Buscar coincidencias</button>';
                            echo '</form>';
                
                            // Botón para iniciar el chat
                            echo '<form action="iniciar_chat.php" method="post">';
                            echo '<input type="hidden" name="reporte_usuario_id" value="' . htmlspecialchars($row_mascota["usuario_id"]) . '">';
                            echo '<input type="hidden" name="reporte_id" value="' . htmlspecialchars($row_mascota["id_mascota"]) . '">'; // Agregar el ID del reporte
                            echo '<button type="submit" class="btn btn-secondary mt-3">Chatear</button>';
                            echo '</form>';
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
