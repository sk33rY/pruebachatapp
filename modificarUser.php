<?php
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Nombre_completo = isset($_POST['Nombres']) ? $_POST['Nombres'] : '';
    $numero_telefono = isset($_POST['numero_telefono']) ? $_POST['numero_telefono'] : '';
    $correo = isset($_POST['correo']) ? $_POST['correo'] : '';
    $fecha_nacimiento = isset($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : '';
    $direccion_residencia = isset($_POST['direccion_residencia']) ? $_POST['direccion_residencia'] : '';

    if ($Nombre_completo && $numero_telefono && $correo && $fecha_nacimiento && $direccion_residencia) {
        $stmt = $conn->prepare("UPDATE usuario SET Nombre_completo=?, numero_telefono=?, correo=?, fecha_nacimiento=?, direccion_residencia=? WHERE correo=?");
        $stmt->bind_param("ssssss", $Nombre_completo, $numero_telefono, $correo, $fecha_nacimiento, $direccion_residencia, $_SESSION['correo']);

        if ($stmt->execute()) {
            header("Location: inicio.php");
            exit;
        } else {
            echo '<p>Error al actualizar los datos: ' . $stmt->error . '</p>';
        }

        $stmt->close();
    } else {
        echo '<p>Por favor, complete todos los campos.</p>';
    }
}

$conn->close();
?>

       

   





