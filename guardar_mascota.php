<?php
include('conexion.php');
session_start();

if (!isset($_SESSION['correo'])) {
    header("Location: inicio.php");
    exit;
}

// Verificar que la conexión a la base de datos esté establecida
if (!isset($conn)) {
    die("Error: No se pudo conectar a la base de datos.");
}

// Obtener el ID del usuario de la sesión
$user = $_SESSION['correo'];
$sqlUser = "SELECT id_usuario FROM usuario WHERE correo='$user'";
$resultUser = $conn->query($sqlUser);
if ($resultUser->num_rows > 0) {
    $rowUser = $resultUser->fetch_assoc();
    $usuario_id = $rowUser['id_usuario'];
} else {
    die("Error: Usuario no encontrado.");
}


// Recuperar y escapar las variables
$nombre = $conn->real_escape_string($_POST['nombre']);
$descripcion = $conn->real_escape_string($_POST['descripcion']);
$raza = $conn->real_escape_string($_POST['raza']);
$tamano = $conn->real_escape_string($_POST['tamano']); 
$color = $conn->real_escape_string($_POST['color']);
$sexo = $conn->real_escape_string($_POST['sexo']);
$lat = isset($_POST['lat']) ? floatval($_POST['lat']) : 0.0;
$lng = isset($_POST['lng']) ? floatval($_POST['lng']) : 0.0;
$tipo = $conn->real_escape_string($_POST['tipo']);
$tipo_animal = $conn->real_escape_string($_POST['tipo_animal']);

// Manejo de la imagen
$imagen = null;
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $fileInfo = getimagesize($_FILES['imagen']['tmp_name']);
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    if (in_array($fileInfo['mime'], $allowedTypes)) {
        $imagen = base64_encode(file_get_contents($_FILES['imagen']['tmp_name']));
    } else {
        die("Error: Solo se permiten imágenes en formato JPEG, PNG o GIF.");
    }
}

// Construir la consulta SQL
$sql = "INSERT INTO mascotas (nombre, descripcion, raza, tamano, color, sexo, tipo_animal, imagen, lat, lng, tipo, usuario_id) 
        VALUES ('$nombre', '$descripcion', '$raza', '$tamano', '$color', '$sexo', '$tipo_animal', '$imagen', $lat, $lng, '$tipo', $usuario_id)";


if ($conn->query($sql) === TRUE) {
    // Redirigir a 'inicio.php' después de crear el registro exitosamente
    echo "Nuevo registro creado con éxito";
    header("Location: inicio.php");
    exit; // Es importante usar exit después de header para evitar que el script continúe ejecutándose
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
