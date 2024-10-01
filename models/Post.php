<?php

/**
 * @OA\Info(title="PDO PHP REST API", version="1.0")
 */
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


    /**
     * @OA\Get(
     *     path="/Swagger/api/post/posts.php",
     *     summary = "Método para obter todos os posts",
     *     tags = {"Posts"},
     *     @OA\Response(response="200", description="Buscou todos os itens"),
     *     @OA\Response(response="404", description="Não encontrado")
     * )
     */
    public function read() {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }


    /**
     * @OA\Get(
     *     path="/Swagger/api/post/single.php",
     *     summary = "Método para obter um único post",
     *     tags = {"Posts"},
     *     @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=true, 
     *      description="O ID informado será buscado nos posts", 
     *      @OA\Schema(
     *          type="string"
     *      ),
     *     ),
     *     @OA\Response(response="200", description="Encontrou o post com o ID informado"),
     *     @OA\Response(response="404", description="Não encontrado o post com o ID informado")
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/Swagger/api/post/insert.php",
     *     summary="Método para criar um post",
     *     tags={"Posts"},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  required={"title", "body", "author"},
     *                  @OA\Property(
     *                      property="title",
     *                      type="string",
     *                      description="Título do post"
     *                  ),
     *                  @OA\Property(
     *                      property="body",
     *                      type="string",
     *                      description="Conteúdo do post"
     *                  ),
     *                  @OA\Property(
     *                      property="author",
     *                      type="string",
     *                      description="Autor do post"
     *                  ),
     *              )
     *          )
     *     ),
     *     @OA\Response(response="200", description="Post criado com sucesso"),
     *     @OA\Response(response="404", description="Post não encontrado")
     * )
     */

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


    /**
     * @OA\Put(
     *     path="/Swagger/api/post/update.php",
     *     summary="Método para atualizar um post",
     *     tags={"Posts"},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="id",
     *                      type="integer",
     *                      description="Id do post",
     *                      example=1
     *                  ),
     *                  @OA\Property(
     *                      property="title",
     *                      type="string",
     *                      description="Título do post",
     *                      example="Novo título do post"
     *                  ),
     *                  @OA\Property(
     *                      property="body",
     *                      type="string",
     *                      description="Conteúdo do post",
     *                      example="Novo conteúdo do post"
     *                  ),
     *                  @OA\Property(
     *                      property="author",
     *                      type="string",
     *                      description="Autor do post",
     *                      example="Novo autor do post"
     *                  ),
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Post atualizado com sucesso"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Post não encontrado"
     *     )
     * )
     */

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


    /**
     * @OA\Delete(
     *     path="/Swagger/api/post/destroy.php",
     *     summary="Método para deletar um post",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="ID do post a ser deletado",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Post deletado com sucesso"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Post não encontrado"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Requisição inválida, verifique o ID do post"
     *     )
     * )
     */
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
