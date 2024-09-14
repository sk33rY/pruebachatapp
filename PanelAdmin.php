
<?php
include("conexion.php");
$con=conectar();
session_start();
if(!isset($_SESSION['correo'])){
header("location:IniciarSesion.php");
}
$user = $_SESSION['correo'];
$sql = "SELECT Nombre_completo, correo FROM usuario WHERE
correo='$user'";
$resultado = mysqli_query($con, $sql) or die (mysqli_error($con));
$row =$resultado->fetch_assoc();
?>

<?php

include_once('conexion.php');
$conect=conectar();
$sql="SELECT * FROM usuario";
$query=mysqli_query($conect,$sql);
$row=mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Formulario </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="imagenes/iconoApp.ico">
    <link rel="stylesheet" href="estilos/registro.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" 
        rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" 
        crossorigin="anonymous">
    </head>
    <body>
            <div class="container mt-5">
                    <div class="row"> 
                        
                        <div class="col-md-3">
                            
                        

                        </div>

                        <div class="col-md-8">
                            <table class="table" >
                                <thead class="table-success table-striped" >
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Nombre</th>
                                        <th>correo</th>
                                        <th>celular</th>
                                        <th>Fecha de nacimiento</th>
                                        <th>Direccion</th>
                                        <th>Rol</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    
                                        <?php while($row=mysqli_fetch_array($query)):?>
                                            <tr>
                                                <th><?php  echo $row['id_usuario']?></th>
                                                <th><?php  echo $row['Nombre_completo']?></th>
                                                <th><?php  echo $row['numero_telefono']?></th>
                                                <th><?php  echo $row['correo']?></th>   
                                                <th><?php  echo $row['fecha_nacimiento']?></th>  
                                                <th><?php  echo $row['direccion_residencia']?></th>
                                                <th><?php  echo $row['rol']?></th>  
                                                <th><a href="ActualizarUser.php?id=<?php echo $row['id_usuario'] ?>" class="btn btn-info">Editar</a></th>
                                                <th><a href="borrarUser.php?id=<?php echo $row['id_usuario'] ?>" class="btn btn-danger">Eliminar</a></th>                                        
                                            </tr>
                                        <?php endwhile;?>
                                        
                                </tbody>
                                <a  class= "btn" href="cerrar.php">salir de Administrador</a>
                            </table>
                        </div>

                    </div>  
            </div>
    </body>
</html>
