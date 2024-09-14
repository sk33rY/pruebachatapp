
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuentra a tu mascota</title>
    <link rel="icon" href="imagenes/iconoApp.ico">
    <link rel="stylesheet" href="estilos/registro.css">
</head>

<body>

    <div id="FormDatos">
        <center>
            <fieldset>

                <section class="form-register">
                    <h4>Formulario de registro</h4>
                <form action="RegistroUser.php" method="POST" name="formulario">
                  
                    
                    <input class= "controls" type=" text" name="Nombres" placeholder="Ingrese su nombre completo" id="Nombres" required>
                    <br>
                    <br>
                    
                    <input class= "controls" type="email" name="correo" placeholder="Ingrese su correo" id="correo" required>
                    <br>
                    <br>
                    
                    <input class= "controls" type="tel" name="telefono" placeholder="Ingrese su celular" id="telefono" pattern="^3[0-9]{9}$" required>
                    <br>
                    <br>
                    
                    <input class= "controls" type="date" name="fecha_nacimiento" placeholder="Ingrese su fecha de nacimiento" id="fecha_nacimiento" required>
                    <br>
                    <br>
                   
                    <input class= "controls" type="text" name="direccion_residencia" placeholder="Ingrese su direccion" id="direccion_residencia" required>
                    <br>
                    <br>
                    
                    <input class= "controls" type="password" name="password" placeholder="Ingrese su contraseña" id="password" required>
                    <br>
                    <br>
                    
                    <input class= "controls" type="password" name="rePassword" placeholder="reingrese su contraseña" id="rePassword" required>
                    <br>
                    <br>
                    <input class= "botons" type="submit" name="submit value=" Registrar>

                    <p> <a href="IniciarSesion.php">¿Ya tienes cuenta?</a></p>
                    </section>
                </form>
                <?php
        if (isset ($_POST['submit'])) {
          require ('RegistroUser.php');
        
        }
        ?>

    

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

</html>