<?php
$nombres = $_POST['Nombres'];
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$direccion_residencia = $_POST['direccion_residencia'];
$password = $_POST['password'];
$repassword = $_POST['rePassword'];
$reqlen = strlen($nombres) * strlen ($correo) * strlen ($telefono) * strlen ($fecha_nacimiento)* strlen ($direccion_residencia)* strlen ($password) * strlen ($repassword);


if ($reqlen > 0){
    if ($password === $repassword) {
        $mysql=new mysqli("localhost","root","","mydb");
         $password = md5($password);
         $nombres= mysqli_real_escape_string($mysql,$nombres);
         $telefono= mysqli_real_escape_string($mysql,$telefono);
         $correo= mysqli_real_escape_string($mysql,$correo);         
         $fecha_nacimiento= mysqli_real_escape_string($mysql,$fecha_nacimiento);
         $direccion_residencia= mysqli_real_escape_string($mysql,$direccion_residencia);
         $password= mysqli_real_escape_string($mysql,$password);
         $resultado= mysqli_query($mysql, 'INSERT INTO usuario
          (Nombre_completo,	numero_telefono, correo, fecha_nacimiento,	direccion_residencia,	contrasenia) VALUES ("' .$nombres . '", "' . $telefono . '","' .$correo . '", "' .$fecha_nacimiento. '", "' .$direccion_residencia. '", "' . $password . '")');
            	
         if($resultado){
          
            echo "<script>alert('usuario creado correctamente');</script>";
            require('inicio.php');
     
      
         }else
         echo(' error creando usuario');
         
         mysqli_close($mysql);
         
       

    } else {
        echo " Por favor, introduzca dos contraseñas identicas.";
    }

}else {
    echo "Por favor, rellene todos los campos requeridos.";
}



?>