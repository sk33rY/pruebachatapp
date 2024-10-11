<?php
include("conexion.php");
session_start();

if (isset($_SESSION['id_usuario'])) {
    include("header_login.php");
} else {
    include("header.php");
}

// Iniciar consulta SQL
$sql = "SELECT m.id_mascota, m.nombre, m.descripcion, m.raza, m.tamano, m.color, m.sexo, 
        m.imagen, m.lat, m.lng, m.tipo, m.usuario_id, u.Nombre_completo AS nombre_usuario
        FROM mascotas m
        JOIN usuario u ON m.usuario_id = u.id_usuario
        WHERE 1=1";

if (!empty($_GET['nombre'])) {
    $nombre = $conn->real_escape_string($_GET['nombre']);
    $sql .= " AND m.nombre LIKE '%$nombre%'";
}
// Aplicar filtros si están presentes
if (!empty($_GET['raza'])) {
    $raza = $conn->real_escape_string($_GET['raza']);
    $sql .= " AND m.raza = '$raza'";
}

if (!empty($_GET['tamano'])) {
    $tamano = $conn->real_escape_string($_GET['tamano']);
    $sql .= " AND m.tamano = '$tamano'";
}

if (!empty($_GET['sexo'])) {
    $sexo = $conn->real_escape_string($_GET['sexo']);
    $sql .= " AND m.sexo = '$sexo'";
}

if (!empty($_GET['tipo_animal'])) {
    $tipo_animal = $conn->real_escape_string($_GET['tipo_animal']);
    $sql .= " AND m.tipo_animal = '$tipo_animal'";
}

if (!empty($_GET['tipo'])) {
    $tipo = $conn->real_escape_string($_GET['tipo']);
    $sql .= " AND m.tipo = '$tipo'";
}

$sql .= " ORDER BY m.fecha_registro DESC";

$sql = $conn->prepare($sql);
$sql->execute();
$result = $sql->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PETLOVER Catalogo</title>
    <link rel="stylesheet" href="estilos/catalogo2.css">
</head>
<body>
    <main>
        <section class="catalogo-container">
            <!-- Barra de filtros -->
            <aside class="filters">
                <h1>¿Qué buscas?</h1>
                <form method="GET" action="catalogo.php">
                    <div class="filter-group filter-buttons">
                        <button type="button" class="filter-btn" id="btn-perro" onclick="seleccionarTipo('perro')">
                            <img src="Imagenes/perritoicono.png" alt="Perro" class="btn-icon"> Perro
                        </button>
                        <button type="button" class="filter-btn" id="btn-gato" onclick="seleccionarTipo('gato')">
                            <img src="Imagenes/gato_icono.png" alt="Gato" class="btn-icon"> Gato
                        </button>
                        <button type="button" class="filter-btn" id="btn-otro" onclick="seleccionarTipo('otro')">
                            <img src="Imagenes/otros_icono.png" alt="Otro" class="btn-icon"> Otro
                        </button>
                        <input type="hidden" name="tipo_animal" id="tipo_animal">
                    </div>
                    <div class="filter-group">
                        <h3>Raza</h3>
                        <select name="raza" id="raza" class="form-select">
                            <option value="">Cualquier raza</option>
                            <!-- Las opciones de raza se cargarán dinámicamente -->
                        </select>
                    </div>
                    <div class="filter-group">
                        <h3>Tamaño</h3>
                        <select name="tamano" class="form-select">
                            <option value="">Cualquier tamaño</option>
                            <option value="pequeño">Pequeño</option>
                            <option value="mediano">Mediano</option>
                            <option value="grande">Grande</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <h3>Sexo</h3>
                        <select name="sexo" class="form-select">
                            <option value="">Cualquier sexo</option>
                            <option value="macho">Macho</option>
                            <option value="hembra">Hembra</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <h3>Tipo</h3>
                        <select name="tipo" class="form-select">
                            <option value="">Perdido o encontrado</option>
                            <option value="perdido">Perdido</option>
                            <option value="encontrado">Encontrado</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </form>
            </aside>

            <!-- Sección de tarjetas -->
            <div class="catalogo">
                <?php
                if ($result->num_rows > 0) {
                    while ($row_mascota = $result->fetch_assoc()) {
                        echo '<div class="card">';
                        echo '<div class="card-header">';
                        echo '<img src="data:image/jpeg;base64,' . htmlspecialchars($row_mascota["imagen"]) . '" alt="Imagen de ' . htmlspecialchars($row_mascota["nombre"]) . '">';
                        echo '<button class="favorite-btn"><ion-icon name="heart-outline"></ion-icon></button>';
                        echo '</div>';
                        echo '<div class="card-body">';
                        echo '<h3>' . htmlspecialchars($row_mascota["nombre"]) . '</h3>';
                        echo '<p class="age">' . htmlspecialchars($row_mascota["tipo"]) . ' &bull; ' . htmlspecialchars($row_mascota["raza"]) . '</p>';
                        echo '<button class="info-btn" onclick="openModal(' . htmlspecialchars($row_mascota["id_mascota"]) . ')">Más Información</button>';
                        
                        // Añadimos el formulario de buscar coincidencias
                        echo '<form action="buscar_coincidencias.php" method="post">';
                        echo '<input type="hidden" name="id_mascota" value="' . htmlspecialchars($row_mascota["id_mascota"]) . '">';
                        echo '<button type="submit" class="btn btn-primary mt-3">Buscar coincidencias</button>';
                        echo '</form>';

                        // Añadimos el formulario para iniciar chat

                        echo '</div>';
                        echo '</div>';

                        // Modal para mostrar más información
                    echo '<div id="modal-' . htmlspecialchars($row_mascota["id_mascota"]) . '" class="modal">';
                    echo '<div class="modal-content">';
                    echo '<span class="close" onclick="closeModal(' . htmlspecialchars($row_mascota["id_mascota"]) . ')">&times;</span>';
                    echo '<div class="modal-header">';
                    echo '<h2>' . htmlspecialchars($row_mascota["nombre"]) . '</h2>';
                    echo '<span class="badge">' . htmlspecialchars($row_mascota["tipo"]) . '</span>';
                    echo '</div>';
                    echo '<div class="modal-body">';
                    echo '<img src="data:image/jpeg;base64,' . htmlspecialchars($row_mascota["imagen"]) . '" alt="Imagen de ' . htmlspecialchars($row_mascota["nombre"]) . '" class="modal-image">';
                    echo '<p><strong>Raza:</strong> ' . htmlspecialchars($row_mascota["raza"]) . '</p>';
                    echo '<p><strong>Tamaño:</strong> ' . htmlspecialchars($row_mascota["tamano"]) . '</p>';
                    echo '<p><strong>Color:</strong> ' . htmlspecialchars($row_mascota["color"]) . '</p>';
                    echo '<p><strong>Sexo:</strong> ' . htmlspecialchars($row_mascota["sexo"]) . '</p>';
                    echo '<p><strong>Descripción:</strong> ' . htmlspecialchars($row_mascota["descripcion"]) . '</p>';
                    echo '<p><strong>Reportado por:</strong> ' . htmlspecialchars($row_mascota["nombre_usuario"]) . '</p>';

                    // Botón para iniciar el chat dentro del modal
                    echo '<form action="chat.php" method="post">';
                    echo '<input type="hidden" name="reporte_usuario_id" value="' . htmlspecialchars($row_mascota["usuario_id"]) . '">';
                    echo '<input type="hidden" name="reporte_id" value="' . htmlspecialchars($row_mascota["id_mascota"]) . '">';
                    echo '<button type="submit" class="btn btn-secondary mt-3">Chatear</button>';
                    echo '</form>';

                    echo '</div>'; // Cierre del modal-body
                    echo '</div>'; // Cierre del modal-content
                    echo '</div>'; // Cierre del modal

                    }
                } else {
                    echo "<p>No hay mascotas registradas.</p>";
                }
                ?>
            </div>
        </section>
    </main>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script>
        // Función para abrir el modal
        function openModal(id) {
            document.getElementById('modal-' + id).style.display = 'block';
        }

        // Función para cerrar el modal
        function closeModal(id) {
            document.getElementById('modal-' + id).style.display = 'none';
        }

        // Función para cargar las razas según el tipo de animal y gestionar la selección de botones
        function seleccionarTipo(tipoAnimal) {
            const buttons = document.querySelectorAll('.filter-btn');
            buttons.forEach(btn => {
                btn.classList.remove('selected');
            });

            const selectedButton = document.getElementById(`btn-${tipoAnimal}`);
            selectedButton.classList.add('selected');

            cargarRazas(tipoAnimal);
        }

        // Función para cargar las razas según el tipo de animal
        function cargarRazas(tipoAnimal) {
            const razaSelect = document.getElementById('raza');
            document.getElementById('tipo_animal').value = tipoAnimal;
            razaSelect.innerHTML = '<option value="">Cualquier raza</option>'; // Reiniciar las opciones

            let apiURL = '';

            if (tipoAnimal === 'perro') {
                apiURL = "https://dog.ceo/api/breeds/list/all";
                fetch(apiURL)
                    .then(response => response.json())
                    .then(data => {
                        for (const raza in data.message) {
                            const option = document.createElement('option');
                            option.value = raza;
                            option.textContent = raza.charAt(0).toUpperCase() + raza.slice(1);
                            razaSelect.appendChild(option);
                        }
                    });
            } else if (tipoAnimal === 'gato') {
                apiURL = "https://api.thecatapi.com/v1/breeds";
                fetch(apiURL)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(raza => {
                            const option = document.createElement('option');
                            option.value = raza.id;
                            option.textContent = raza.name;
                            razaSelect.appendChild(option);
                        });
                    });
            } else {
                const otrasRazas = ["Conejo", "Hámster", "Loro", "Pez dorado", "Tortuga"];
                otrasRazas.forEach(raza => {
                    const option = document.createElement('option');
                    option.value = raza.toLowerCase();
                    option.textContent = raza;
                    razaSelect.appendChild(option);
                });
            }
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
