<?php

// Inclui o Model Review.
// __DIR__ garante que o caminho seja absoluto baseado na pasta atual.
require_once(__DIR__ . "/../models/Review.php");

// Classe responsável por manipular os dados da tabela "reviews"
class ReviewDao {

    // Propriedade que armazena a conexão com o banco (PDO)
    private $conn;

    // Construtor recebe a conexão PDO ao instanciar a classe
    public function __construct(PDO $conn) {
        $this->conn = $conn; // Armazena a conexão na propriedade da classe
    }

    // Método responsável por transformar um array do banco em um objeto Review
    public function buildReview($data) {
        $reviewObject = new Review(); // Cria um novo objeto Review
        
        // Preenche as propriedades do objeto com os dados vindos do banco
        $reviewObject->id = $data["id"];
        $reviewObject->rating = $data["rating"];
        $reviewObject->review = $data["review"];
        $reviewObject->users_id = $data["users_id"];
        $reviewObject->movies_id = $data["movies_id"];

        // Retorna o objeto já preenchido
        return $reviewObject;
    }

    public function create(Review $review) {
        // Adiciona uma nova review

        // Antes de inserir, verifica se o usuário já avaliou esse filme
        // Se já avaliou, impede a inserção e retorna false
        if($this->hasAlreadyReviewed($review->movies_id, $review->users_id)) {
            return false;
        }

        // Prepara a query para inserir uma nova review
        $stmt = $this->conn->prepare("
            INSERT INTO reviews (rating, review, users_id, movies_id)
            VALUES (:rating, :review, :users_id, :movies_id)
        ");

        // Associa os valores do objeto Review aos parâmetros da query
        $stmt->bindParam(":rating", $review->rating);
        $stmt->bindParam(":review", $review->review);
        $stmt->bindParam(":users_id", $review->users_id);
        $stmt->bindParam(":movies_id", $review->movies_id);

        // Executa a inserção
        $stmt->execute();

        // Retorna true indicando que a review foi criada com sucesso
        return true;
    }

    public function getMoviesReview($id) {
        // Retorna todas as reviews de um filme específico

        // Prepara a query filtrando pelo ID do filme
        $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE movies_id = :movies_id");
        
        // Associa o parâmetro ao valor correspondente
        $stmt->bindParam(":movies_id", $movies_id);
        
        // Executa a consulta
        $stmt->execute();

        // Array que armazenará os objetos Review
        $reviews = [];

        // Converte cada registro retornado em objeto Review
        foreach($stmt->fetchAll() as $data) {
            $reviews[] = $this->buildReview($data);
        }

        // Retorna todas as reviews encontradas
        return $reviews;
    }

    public function hasAlreadyReviewed($id, $userId) {
        // Verifica se o usuário já fez review desse filme

        // Prepara a query buscando uma review com mesmo filme e mesmo usuário
        $stmt = $this->conn->prepare("
            SELECT id FROM reviews 
            WHERE movies_id = :movies_id AND users_id = :user_id
        ");

        // Associa os parâmetros da query
        $stmt->bindParam(":movies_id", $movies_id);
        $stmt->bindParam(":user_id", $user_id);
        
        // Executa a consulta
        $stmt->execute();

        // Se existir pelo menos 1 registro, retorna true
        return $stmt->rowCount() > 0;
    }

    public function getRatings($id) {
        // Calcula a média das avaliações (rating) de um filme específico

        $stmt = $this->conn->prepare("
            SELECT rating  
            FROM reviews 
            WHERE movies_id = :id
        ");

        // Associa o ID do filme ao parâmetro
        $stmt->bindParam(":id", $id);
        
        // Executa a consulta
        $stmt->execute();

        // Verifica se existem avaliações para o filme
        if($stmt->rowCount() > 0) {
            
            // Pega todas as notas
            $ratings = $stmt->fetchAll();
            
            $total = 0; // Variável para somar as notas
            
            // Soma todas as avaliações
            foreach($ratings as $item){
                $total += $item["rating"];
            }

            // Calcula a média
            $media = $total / count($ratings);

            // Retorna a média arredondada para 1 casa decimal
            return round($media, 1);
        }

        // Se não houver avaliações, retorna 0
        return 0;
    }
 
}
