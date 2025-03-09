<?php
class DatabaseConnection {
    public $conn;  // Declarada como pública para evitar el aviso de Intelephense
    private $maxRetries = 3;
    
    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "secret_ops");
        $this->conn->set_charset("utf8mb4");
        
        if ($this->conn->connect_error) {
            throw new Exception("Error de conexión: " . $this->conn->connect_error);
        }
    }
    
    public function query($sql) {
        $retry = 0;
        while ($retry < $this->maxRetries) {
            try {
                $result = $this->conn->query($sql);
                if ($result === false && $this->conn->errno === 2006) {
                    $this->reconnect();
                    $retry++;
                    continue;
                }
                return $result;
            } catch (Exception $e) {
                if ($retry < $this->maxRetries) {
                    $this->reconnect();
                    $retry++;
                } else {
                    throw $e;
                }
            }
        }
        return null;
    }
    
    private function reconnect() {
        $this->conn->close();
        $this->conn = new mysqli("localhost", "root", "", "secret_ops");
        $this->conn->set_charset("utf8mb4");
    }
}

try {
    $db = new DatabaseConnection();
    $conn = $db->conn;
} catch (Exception $e) {
    die("Error al establecer la conexión: " . $e->getMessage());
}
?>