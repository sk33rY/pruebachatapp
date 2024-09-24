<?php
include('conexion.php');
include('config_cifrado.php');

$usuario_id = $_GET['usuario_id'];
$reporte_usuario_id = $_GET['reporte_usuario_id'];

// Comprobar si las variables GET están llegando correctamente
if (empty($usuario_id) || empty($reporte_usuario_id)) {
    die('Error: usuario_id o reporte_usuario_id no proporcionados.');
}

// Obtener los mensajes entre los dos usuarios
$sqlChat = "SELECT emisor_id, mensaje, iv, fecha FROM mensajes WHERE 
           (emisor_id = '$usuario_id' AND receptor_id = '$reporte_usuario_id') 
           OR (emisor_id = '$reporte_usuario_id' AND receptor_id = '$usuario_id') 
           ORDER BY fecha";
$resultChat = $conn->query($sqlChat);

// Comprobar si la consulta a la base de datos devuelve algo
if ($resultChat === false) {
    die("Error en la consulta: " . $conn->error);
}

// Preparar un array para los mensajes
$mensajes = [];

if ($resultChat->num_rows > 0) {
    while ($rowChat = $resultChat->fetch_assoc()) {
        $emisor = $rowChat['emisor_id'] == $usuario_id ? 'Tú' : 'Usuario';

        // Descifrar el mensaje
        $clave = CLAVE_CIFRADO;
        $iv = hex2bin($rowChat['iv']);  // Convertir el IV de hexadecimal a binario
        $mensaje_cifrado = $rowChat['mensaje'];
        $mensaje_descifrado = openssl_decrypt($mensaje_cifrado, 'AES-256-CBC', $clave, 0, $iv);

        // Comprobar si el descifrado fue exitoso
        if ($mensaje_descifrado === false) {
            $mensaje_descifrado = "Error al descifrar el mensaje.";
        }

        // Añadir el mensaje al array
        $mensajes[] = [
            'emisor' => $emisor,
            'mensaje' => htmlspecialchars($mensaje_descifrado),
            'fecha' => $rowChat['fecha']
        ];
    }
} else {
    // No hay mensajes
    $mensajes[] = ['mensaje' => 'No hay mensajes.'];
}

// Enviar los mensajes en formato JSON
header('Content-Type: application/json');
echo json_encode($mensajes);

$conn->close();
?>
