<?php
include("conexion.php");
session_start();

if (!isset($_SESSION['correo'])) {
    header("location:IniciarSesion.php");
    exit;
}

$user = $_SESSION['correo'];

// Obtener la información del usuario actual
$sql = "SELECT Nombre_completo, numero_telefono, correo, fecha_nacimiento, direccion_residencia FROM usuario WHERE correo=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();
} else {
    echo '<p>No se encontraron datos del usuario.</p>';
    exit;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Datos</title>
    <link rel="icon" href="imagenes/iconoApp.ico">
    <link rel="stylesheet" href="estilos/registro.css"> <!-- Archivo CSS personalizado -->
</head>

<body>
    <div class="dashboard">
        <!-- Barra lateral -->
        <aside class="sidebar">
            <div class="logo">
                <h2>PETLOVER</h2>
            </div>
            <nav class="menu">
                <a href="inicio.php">Inicio</a>
                <a href="Mapa.php">Mapa</a>
                <a href="FormReportePet.php">Reportes</a>
                <a href="confiPersonal.php">Configuración Personal</a>
                <a href="Chats.html">Chats</a>
                <a href="IniciarSesion.php">Cerrar Sesión</a>
            </nav>
        </aside>

        <!-- Contenido principal -->
        <div class="main-content">
            <header>
                <h2>Modificar Datos del Usuario</h2>
            </header>
            <div class="content">
                <fieldset>
                    <section class="form-register">
                        <form action="modificarUser.php" method="POST" name="formulario">
                            <label for="Nombres">Nombres y apellidos completos</label>
                            <input class="controls" type="text" name="Nombres" id="Nombres" value="<?php echo htmlspecialchars($row['Nombre_completo']); ?>" required>
                            <label for="correo">Correo Electrónico</label>
                            <input class="controls" type="email" name="correo" id="correo" value="<?php echo htmlspecialchars($row['correo']); ?>" required>
                            <label for="numero_telefono">Número de Celular</label>
                            <input class="controls" type="tel" name="numero_telefono" id="numero_telefono" value="<?php echo htmlspecialchars($row['numero_telefono']); ?>" pattern="^3[0-9]{9}$" required>
                            <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                            <input class="controls" type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="<?php echo htmlspecialchars($row['fecha_nacimiento']); ?>" required>
                            <label for="direccion_residencia">Dirección</label>
                            <input class="controls" type="text" name="direccion_residencia" id="direccion_residencia" value="<?php echo htmlspecialchars($row['direccion_residencia']); ?>" required>
                            <input class="botons" type="submit" name="submit" value="Modificar">
                        </form>
                    </section>
                </fieldset>
            </div>
        </div>
    </div>

    <!-- Script para interactividad -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

</html>
