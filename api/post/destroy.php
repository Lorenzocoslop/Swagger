<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Incluir a conexão com o banco de dados e o modelo Post
include_once '../../config/Database.php';
include_once '../../models/Post.php';

// Instanciar o banco de dados e a classe Post
$database = new Database();
$db = $database->connect();

// Instanciar o objeto Post
$post = new Post($db);

// Capturar o ID do post a ser deletado
// Verificar se o ID foi passado pela URL ou no corpo da requisição
$id = null;

// Tenta capturar o ID via query string (exemplo: destroy.php?id=1)
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

// Tenta capturar o ID no corpo da requisição JSON
$data = json_decode(file_get_contents("php://input"));
if (!empty($data->id)) {
    $id = $data->id;
}

// Verificar se o ID foi fornecido
if (is_null($id)) {
    echo json_encode(array('message' => 'ID do post não fornecido ou inválido.'));
    exit;
}

// Setar o ID do post a ser deletado
$post->id = $id;

// Deletar o post
if($post->delete()) {
    echo json_encode(array('message' => 'Post Deleted'));
} else {
    echo json_encode(array('message' => 'Post Not Deleted'));
}
?>
