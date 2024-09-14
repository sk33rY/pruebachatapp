<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use mysqli;

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "Nueva conexión! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Conexión %d envía mensaje "%s" a %d otras conexiones' . "\n", $from->resourceId, $msg, $numRecv);

        // Decodificar el mensaje JSON recibido
        $data = json_decode($msg, true);
        $mensaje = $data['mensaje'];
        $emisor_id = $data['usuario_id'];
        $receptor_id = $data['receptor_id'];
        $reporte_id = $data['reporte_id'];

        // Conectar a la base de datos
        $connDB = new mysqli('localhost', 'root', '', 'mydb');
        if ($connDB->connect_error) {
            die("Conexión fallida: " . $connDB->connect_error);
        }

        // Insertar el mensaje en la base de datos
        $sqlInsert = "INSERT INTO mensajes (emisor_id, receptor_id, mensaje, iv, fecha, reporte_id) VALUES (?, ?, ?, '', NOW(), ?)";
        $stmtInsert = $connDB->prepare($sqlInsert);
        $stmtInsert->bind_param("iisi", $emisor_id, $receptor_id, $mensaje, $reporte_id);

        if ($stmtInsert->execute()) {
            echo "Mensaje guardado en la base de datos.\n";
        } else {
            echo "Error al guardar el mensaje: " . $stmtInsert->error;
        }

        $stmtInsert->close();
        $connDB->close();

        // Enviar el mensaje a otros clientes
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Conexión {$conn->resourceId} se ha desconectado\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Se ha producido un error: {$e->getMessage()}\n";
        $conn->close();
    }
}
