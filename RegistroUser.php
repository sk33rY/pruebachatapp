<?php
// Mostrar errores de PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Obtener datos del formulario
$nombres = $_POST['Nombres'] ?? '';
$correo = $_POST['correo'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
$direccion_residencia = $_POST['direccion_residencia'] ?? '';
$password = $_POST['password'] ?? '';
$repassword = $_POST['rePassword'] ?? '';

// Verificar si algún campo está vacío
if (empty($nombres) || empty($correo) || empty($telefono) || empty($fecha_nacimiento) || empty($direccion_residencia) || empty($password) || empty($repassword)) {
    echo "<script>alert('Por favor, rellene todos los campos requeridos.'); window.history.back();</script>";
    exit;
}

// Verificar si las contraseñas coinciden
if ($password !== $repassword) {
    echo "<script>alert('Por favor, introduzca dos contraseñas idénticas.'); window.history.back();</script>";
    exit;
}

// Verificar que el usuario tenga al menos 18 años
$fecha_nacimiento_dt = new DateTime($fecha_nacimiento);
$fecha_actual = new DateTime();
$edad = $fecha_actual->diff($fecha_nacimiento_dt)->y;

if ($edad < 18) {
    echo "<script>alert('Debes tener al menos 18 años para registrarte.'); window.history.back();</script>";
    exit;
}

// Conectar a la base de datos
$mysql = new mysqli("localhost", "root", "", "mydb");
if ($mysql->connect_error) {
    echo "<script>alert('Error de conexión a la base de datos.'); window.history.back();</script>";
    exit;
}

// Verificar si el correo electrónico ya existe en la base de datos
$correo = mysqli_real_escape_string($mysql, $correo);
$query_verificar_correo = "SELECT id_usuario FROM usuario WHERE correo = '$correo'";
$result = $mysql->query($query_verificar_correo);

if ($result->num_rows > 0) {
    // Si el correo ya existe, mostrar una alerta y regresar al formulario
    echo "<script>alert('El correo electrónico ya está registrado.'); window.history.back();</script>";
    exit;
}

// Encriptar la contraseña y limpiar los datos
$password_hashed = md5($password);
$nombres = mysqli_real_escape_string($mysql, $nombres);
$telefono = mysqli_real_escape_string($mysql, $telefono);
$fecha_nacimiento = mysqli_real_escape_string($mysql, $fecha_nacimiento);
$direccion_residencia = mysqli_real_escape_string($mysql, $direccion_residencia);

// Insertar nuevo usuario
$query = "INSERT INTO usuario (Nombre_completo, numero_telefono, correo, fecha_nacimiento, direccion_residencia, contrasenia) 
          VALUES ('$nombres', '$telefono', '$correo', '$fecha_nacimiento', '$direccion_residencia', '$password_hashed')";

if ($mysql->query($query) === TRUE) {
    // Redirigir al login después de registro exitoso
    header('Location: iniciose.html?login=true');
    exit;
} else {
    echo "<script>alert('Error creando usuario: " . $mysql->error . "'); window.history.back();</script>";
}

// Cerrar la conexión
$mysql->close();
?>
