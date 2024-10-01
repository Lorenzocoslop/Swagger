<?php
class Database {
    private $host = 'mariadb';
    private $db_name = 'loja';
    private $username = 'root';
    private $password = 'P@ssw0rd';
    public $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Erro de Conexão: ' . $e->getMessage();
        }

        return $this->conn;
    }
}
?>
