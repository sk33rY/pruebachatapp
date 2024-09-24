<?php
include('conexion.php');
include('config_cifrado.php');
session_start();

if (!isset($_SESSION['correo'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$mensaje_original = $_POST['mensaje'];
$emisor_id = $_POST['usuario_id'];
$receptor_id = $_POST['receptor_id'];
$reporte_id = $_POST['reporte_id'];

var_dump($mensaje_original, $emisor_id, $receptor_id, $reporte_id);

// Cifrar el mensaje
$clave = CLAVE_CIFRADO;
$iv = random_bytes(openssl_cipher_iv_length('AES-256-CBC'));
$mensaje_cifrado = openssl_encrypt($mensaje_original, 'AES-256-CBC', $clave, 0, $iv);
$iv_hex = bin2hex($iv);  // Convertimos el IV a hexadecimal para almacenarlo

// Insertar el mensaje cifrado en la base de datos
$sqlInsert = "INSERT INTO mensajes (emisor_id, receptor_id, mensaje, iv, fecha, reporte_id) VALUES (?, ?, ?, ?, NOW(), ?)";
$stmtInsert = $conn->prepare($sqlInsert);
$stmtInsert->bind_param("iisss", $emisor_id, $receptor_id, $mensaje_cifrado, $iv_hex, $reporte_id);

if ($stmtInsert->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al enviar el mensaje.']);
}

$stmtInsert->close();
$conn->close();
?>
