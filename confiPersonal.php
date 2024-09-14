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
    <link rel="stylesheet" href="estilos/registro.css">
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
            <a href="#">Nosotros</a>
            <a href="#">Blog</a>
            <a href="#">Contacto</a>
        </nav>
    </div>
</header>
<div class="capa"></div>
<input type="checkbox" id="btn-menu">
<div class="container-menu">
    <div class="cont-menu">
        <nav>
            <a href="inicio.php">Inicio</a>
            <a href="Mapa.php">Mapa</a>
            <a href="FormReportePet.php">Reportes</a>
            <a href="confiPersonal.php">Configuración Personal</a>
            <a href="Chats.html">Chats</a>
            <a href="IniciarSesion.php">Iniciar Sesión</a>
            <label for="btn-menu">✖️</label>
        </nav>
    </div>
</div>  

<div id="todoCuerpo">
    <center>
        <fieldset>
            <section class="form-register">
                <form action="modificarUser.php" method="POST" name="formulario">
                    <legend><h2>Modificar datos</h2></legend>
                    <br>
                    <label for="Nombres">Nombres y apellidos completos</label>
                    <input class="controls" type="text" name="Nombres" id="Nombres" value="<?php echo htmlspecialchars($row['Nombre_completo']); ?>" required>
                    <br>
                    <br>
                    <label for="correo">Correo Electrónico</label>
                    <input class="controls" type="email" name="correo" id="correo" value="<?php echo htmlspecialchars($row['correo']); ?>" required>
                    <br>
                    <br>
                    <label for="numero_telefono">Número de Celular</label>
                    <input class="controls" type="tel" name="numero_telefono" id="numero_telefono" value="<?php echo htmlspecialchars($row['numero_telefono']); ?>" pattern="^3[0-9]{9}$" required>
                    <br>
                    <br>
                    <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                    <input class="controls" type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="<?php echo htmlspecialchars($row['fecha_nacimiento']); ?>" required>
                    <br>
                    <br>
                    <label for="direccion_residencia">Dirección</label>
                    <input class="controls" type="text" name="direccion_residencia" id="direccion_residencia" value="<?php echo htmlspecialchars($row['direccion_residencia']); ?>" required>
                    <br>
                    <br>
                    <input class="botons" type="submit" name="submit" value="Modificar">
                </form>
            </section>
        </fieldset>
    </center>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>
</html>
