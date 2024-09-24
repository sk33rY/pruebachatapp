<?php
include('conexion.php');
include('config_cifrado.php');
session_start();

if (!isset($_SESSION['correo'])) {
    header("Location: IniciarSesion.php");
    exit;
}

$usuario_id = $_GET['usuario_id'];
$reporte_usuario_id = $_GET['reporte_usuario_id'];
$reporte_id = $_GET['reporte_id'];

// Obtener el nombre del usuario actual
$sqlNombreUsuario = "SELECT nombre_completo FROM usuario WHERE id_usuario = ?";
$stmtNombreUsuario = $conn->prepare($sqlNombreUsuario);
$stmtNombreUsuario->bind_param("i", $usuario_id);
$stmtNombreUsuario->execute();
$resultNombreUsuario = $stmtNombreUsuario->get_result();
$nombreUsuario = $resultNombreUsuario->fetch_assoc()['nombre_completo'] ?? 'Usuario Desconocido'; // Manejo de variable no definida
$stmtNombreUsuario->close();

// Obtener los mensajes entre los dos usuarios
$sqlChat = "SELECT emisor_id, mensaje, iv, fecha FROM mensajes WHERE 
           ((emisor_id = ? AND receptor_id = ?) OR (emisor_id = ? AND receptor_id = ?))
           AND reporte_id = ? 
           ORDER BY fecha";
$stmtChat = $conn->prepare($sqlChat);
$stmtChat->bind_param("iiiii", $usuario_id, $reporte_usuario_id, $reporte_usuario_id, $usuario_id, $reporte_id);
$stmtChat->execute();
$resultChat = $stmtChat->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos/chat.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1 class="text-center mb-4">Chat con <?php echo htmlspecialchars($nombreUsuario); ?></h1>
    <div id="chat-box" class="chat-box">
        <?php
        if (isset($resultChat) && $resultChat->num_rows > 0) {
            while ($rowChat = $resultChat->fetch_assoc()) {
                $emisor = $rowChat['emisor_id'] == $usuario_id ? 'Tú' : 'Usuario';
                
                // Descifrar el mensaje
                $clave = CLAVE_CIFRADO;
                $iv = hex2bin($rowChat['iv']);
                $mensaje_cifrado = $rowChat['mensaje'];
                $mensaje_descifrado = openssl_decrypt($mensaje_cifrado, 'AES-256-CBC', $clave, 0, $iv);
                $timestamp = date('H:i', strtotime($rowChat['fecha']));

                $class = $rowChat['emisor_id'] == $usuario_id ? 'sender' : 'receiver';

                echo '<div class="chat-message ' . htmlspecialchars($class) . '">';
                echo '<strong>' . htmlspecialchars($emisor) . ':</strong> ' . htmlspecialchars($mensaje_descifrado);
                echo '<div class="timestamp">' . htmlspecialchars($timestamp) . '</div>';
                echo '</div>';
            }
        } else {
            echo "<p>No hay mensajes.</p>";
        }
        ?>
    </div>
    <form id="chat-form" class="mt-3">
        <input type="hidden" id="usuario_id" value="<?php echo htmlspecialchars($usuario_id); ?>">
        <input type="hidden" id="receptor_id" value="<?php echo htmlspecialchars($receptor_usuario_id); ?>">
        <input type="hidden" id="reporte_id" value="<?php echo htmlspecialchars($reporte_id); ?>">
        <input type="hidden" id="nombre_usuario" value="<?php echo htmlspecialchars($nombreUsuario); ?>">
        <div class="form-group">
            <textarea class="form-control" id="mensaje" rows="3" placeholder="Escribe tu mensaje..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Enviar</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function () {
        var conn = new WebSocket('ws://localhost:8080');
        var chat = $('#chat-box');
        var form = $('#chat-form');
        var mensaje = $('#mensaje');

        conn.onopen = function(e) {
            console.log("Conexión establecida!");
        };

        conn.onmessage = function(e) {
            var data = JSON.parse(e.data);
            var className = data.emisor === $('#nombre_usuario').val() ? 'sender' : 'receiver';
            chat.append('<div class="chat-message ' + className + '"><strong>' + htmlspecialchars(data.emisor) + ':</strong> ' + htmlspecialchars(data.mensaje) + '<div class="timestamp">' + new Date().toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'}) + '</div></div>');
            chat.scrollTop(chat[0].scrollHeight);
        };

        form.on('submit', function(e) {
            e.preventDefault();
            var msg = mensaje.val();
            if (msg.trim() === '') return;
            
            var data = {
                emisor: $('#nombre_usuario').val(),
                mensaje: msg,
                usuario_id: $('#usuario_id').val(),
                receptor_id: $('#receptor_id').val(),
                reporte_id: $('#reporte_id').val()
            };
            conn.send(JSON.stringify(data));
            chat.append('<div class="chat-message sender"><strong>Tú:</strong> ' + htmlspecialchars(msg) + '<div class="timestamp">' + new Date().toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'}) + '</div></div>');
            chat.scrollTop(chat[0].scrollHeight);
            mensaje.val('');

            // Enviar el mensaje al servidor PHP
            $.post('enviar_mensaje.php', data, function(response) {
                if (!response.success) {
                    alert('Error al enviar el mensaje.');
                }
            }, 'json');
        });

        function htmlspecialchars(str) {
            return $('<div>').text(str).html();
        }
    });
</script>
</body>
</html>
