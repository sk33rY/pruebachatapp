<?php
include('conexion.php');
session_start();

if (!isset($_SESSION['correo'])) {
    header("Location: IniciarSesion.php");
    exit;
}

// Obtener el id del usuario de la sesión actual
$user = $_SESSION['correo'];
$sqlUser = "SELECT id_usuario FROM usuario WHERE correo = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("s", $user);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();

if ($resultUser->num_rows > 0) {
    $rowUser = $resultUser->fetch_assoc();
    $current_user_id = $rowUser['id_usuario'];
} else {
    die("Error: Usuario no encontrado.");
}
$stmtUser->close();

// Consultar los usuarios con los que ha tenido conversaciones (emisor o receptor) y los IDs de los reportes
$sqlConversaciones = "
    SELECT DISTINCT 
        m.reporte_id,
        CASE
            WHEN m.emisor_id = ? THEN m.receptor_id
            WHEN m.receptor_id = ? THEN m.emisor_id
        END AS otro_usuario_id
    FROM mensajes m
    WHERE (m.emisor_id = ? OR m.receptor_id = ?)
";
$stmtConversaciones = $conn->prepare($sqlConversaciones);
$stmtConversaciones->bind_param("iiii", $current_user_id, $current_user_id, $current_user_id, $current_user_id);
$stmtConversaciones->execute();
$resultConversaciones = $stmtConversaciones->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bandeja de Mensajes</title>
    <!-- Incluir Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .conversacion {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1 class="text-center mb-4">Bandeja de Mensajes</h1>

    <?php
    if ($resultConversaciones->num_rows > 0) {
        while ($rowConversacion = $resultConversaciones->fetch_assoc()) {
            $otro_usuario_id = $rowConversacion['otro_usuario_id'];
            $reporte_id = $rowConversacion['reporte_id']; // Obtener el ID del reporte

            // Obtener información del otro usuario
            $sqlOtroUsuario = "SELECT nombre_completo FROM usuario WHERE id_usuario = ?";
            $stmtOtroUsuario = $conn->prepare($sqlOtroUsuario);
            $stmtOtroUsuario->bind_param("i", $otro_usuario_id);
            $stmtOtroUsuario->execute();
            $resultOtroUsuario = $stmtOtroUsuario->get_result();
            
            if ($resultOtroUsuario->num_rows > 0) {
                $rowOtroUsuario = $resultOtroUsuario->fetch_assoc();
                $nombre_otro_usuario = $rowOtroUsuario['nombre_completo']; // Cambia esto según la columna correcta
            
                echo '<div class="conversacion bg-white">';
                echo '<h5>Conversación con ' . htmlspecialchars($nombre_otro_usuario) . '</h5>';
                echo '<form action="chat.php" method="get">';
                echo '<input type="hidden" name="usuario_id" value="' . htmlspecialchars($current_user_id) . '">';
                echo '<input type="hidden" name="reporte_usuario_id" value="' . htmlspecialchars($otro_usuario_id) . '">';
                echo '<input type="hidden" name="reporte_id" value="' . htmlspecialchars($reporte_id) . '">'; // Agregar el ID del reporte
                echo '<button type="submit" class="btn btn-primary">Ver Conversación</button>';
                echo '</form>';
                echo '</div>';            
            }
            $stmtOtroUsuario->close();
        }
    } else {
        echo '<p>No tienes conversaciones recientes.</p>';
    }
    ?>
</div>
</body>
</html>
<?php
$stmtConversaciones->close();
$conn->close();
?>
