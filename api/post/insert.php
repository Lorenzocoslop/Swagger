<?php
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Post.php';

// Inicializa a conexão com o banco de dados
$database = new Database();
$db = $database->connect();

// Inicializa um novo post
$post = new Post($db);

// Verifica se os campos foram enviados e atribui aos atributos
$post->title = isset($_POST['title']) ? $_POST['title'] : null;
$post->body = isset($_POST['body']) ? $_POST['body'] : null;
$post->author = isset($_POST['author']) ? $_POST['author'] : null;

// Verifica se todos os campos obrigatórios foram preenchidos
if ($post->title && $post->body && $post->author) {
    if ($post->create()) {
        echo json_encode(['message' => 'Post criado com sucesso.']);
    } else {
        echo json_encode(['message' => 'Erro ao criar o post.']);
    }
} else {
    echo json_encode(['message' => 'Campos obrigatórios não preenchidos.']);
}
?>
