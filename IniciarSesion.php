<?php
include("conexion.php");
session_start();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>find your pet</title>
    <link rel="icon" href="Imagenes/iconoApp.ico">
    <link rel="stylesheet" href="estilos/iniciosesion.css">
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
<!--	--------------->
<input type="checkbox" id="btn-menu">
<div class="container-menu">
	<div class="cont-menu">
    <nav>
    <a href="inicio.php">Inicio</a>
        <a href="Mapa.php">Mapa</a>
        <a href="FormReportePet.php">Reportes</a>
        <a href="confiPersonal.php">configuración personal</a>
        <a href="Chats.html">Chats</a>
        <a href="IniciarSesion.php">Iniciar Sesion</a>
		<label for="btn-menu">✖️</label>
        </nav>
    </div>
</div>

            <div id="FormIniciar">
                <center>
                    <fieldset>
                        <section class="form-login">
                            <h4>Bienvenidos a PetLovers</h4>
                        <form action="login.php" method="post">
                            
                            <input  class= "controls" type=" email" name="correo" placeholder="Ingrese su correo" id="correo" required><br>
                            <br>
                            <br>

                            <input  class= "controls" type="password" name="password" placeholder="Ingresa tu contraseña" id="password" required>
                            <br>
                            <br>
                           
                            <input  class= "botons" type="submit" name="login" value="login">
                            <td>&nbsp;</td>

                            <p> <a href="FormRegistroUser.php">¿No tienes cuenta?</a></p>
                            
                            </section>
                        </form>
                      

                </center>

            </div>

            </section>

            </center>


            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

                </center>
                </fieldset>
    </div>
    </div>
</body>