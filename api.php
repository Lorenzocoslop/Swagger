<?php
header("Content-Type: application/json; charset=UTF-8");
include_once 'product.php';

$product = new Product();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $result = $product->getProduct($_GET['id']);
            echo json_encode($result);
        } else {
            $result = $product->getAllProducts();
            echo json_encode($result);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if ($product->createProduct($data)) {
            echo json_encode(["message" => "Produto criado com sucesso!"]);
        } else {
            echo json_encode(["message" => "Falha ao criar produto."]);
        }
        break;

    case 'PUT':
        $id = $_GET['id'];
        $data = json_decode(file_get_contents("php://input"), true);
        if ($product->updateProduct($id, $data)) {
            echo json_encode(["message" => "Produto atualizado com sucesso!"]);
        } else {
            echo json_encode(["message" => "Falha ao atualizar produto."]);
        }
        break;

    case 'DELETE':
        $id = $_GET['id'];
        if ($product->deleteProduct($id)) {
            echo json_encode(["message" => "Produto deletado com sucesso!"]);
        } else {
            echo json_encode(["message" => "Falha ao deletar produto."]);
        }
        break;

    default:
        echo json_encode(["message" => "Método não permitido."]);
        break;
}
?>
