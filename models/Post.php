<?php
class Post {
    private $conn;
    private $table = 'posts';

    public $id;
    public $title;
    public $body;
    public $author;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para obter todos os posts
    public function read() {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Método para obter um único post
    public function read_single() {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ? LIMIT 0,1';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->title = $row['title'] ?? null;
        $this->body = $row['body'] ?? null;
        $this->author = $row['author'] ?? null;
        $this->created_at = $row['created_at'] ?? null;
    }

    // Método para criar um post
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' SET title = :title, body = :body, author = :author';
        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));

        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':body', $this->body);
        $stmt->bindParam(':author', $this->author);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Método para atualizar um post
    public function update() {
        $query = 'UPDATE ' . $this->table . ' SET title = :title, body = :body, author = :author WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':body', $this->body);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Método para deletar um post
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
