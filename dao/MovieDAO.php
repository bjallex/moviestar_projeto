<?php

// Inclui o arquivo do Model Movie.
// __DIR__ garante que o caminho seja absoluto baseado na pasta atual.
require_once(__DIR__ . "/../models/Movie.php");

// Classe responsável por acessar e manipular dados da tabela "movies"
class MovieDAO {

    // Conexão com o banco de dados (PDO)
    private $conn;

    // Construtor recebe a conexão PDO ao instanciar a classe
    public function __construct(PDO $conn) {
        $this->conn = $conn; // Armazena a conexão na propriedade da classe
    }

    // Método responsável por transformar um array do banco em um objeto Movie
    public function buildMovie($data) {
        $movie = new Movie(); // Cria um novo objeto Movie
        
        // Preenche as propriedades do objeto com os dados vindos do banco
        $movie->id = $data["id"];
        $movie->title = $data["title"];
        $movie->description = $data["description"];
        $movie->image = $data["image"];
        $movie->trailer = $data["trailer"];
        $movie->category = $data["category"];
        $movie->length = $data["length"];
        $movie->users_id = $data["users_id"];
        
        // Retorna o objeto já preenchido
        return $movie;
    }

    public function findAll() {
        // Retorna todos os filmes do banco

        // Executa uma query direta (não preparada)
        $stmt = $this->conn->query("SELECT * FROM movies ");
        
        // Array que armazenará os objetos Movie
        $movie=[];
        
        // Percorre todos os registros retornados
        foreach($stmt->fetchAll() as $data) {
            // Converte cada registro em objeto Movie e adiciona no array
            $movie[] = $this->buildMovie($data);
        }

        // Retorna o array de objetos Movie
        return $movie;
    }

    public function getLatestMovies() {
        // Retorna os 10 filmes mais recentes (ordenados por ID decrescente)

        $stmt = $this->conn->query("SELECT * FROM movies ORDER BY id DESC LIMIT 10");
        
        $movie = [];

        // Converte cada registro em objeto Movie
        foreach($stmt->fetchAll() as $data) {
            $movie[] = $this->buildMovie($data);
        }

        return $movie;
    }

    public function getMoviesByCategory($category) {
        // Retorna filmes filtrados por categoria

        // Prepara a query para evitar SQL Injection
        $stmt = $this->conn->prepare("SELECT * FROM movies WHERE category = :category");
        
        // Associa o valor recebido ao parâmetro :category
        $stmt->bindParam(":category", $category);
        
        // Executa a query
        $stmt->execute();

        $movie = [];

        // Converte cada resultado em objeto Movie
        foreach($stmt->fetchAll() as $data) {
            $movie[] = $this->buildMovie($data);
        }

        return $movie;
    }

    public function getMoviesByUserId($id) {
        // Retorna filmes cadastrados por um usuário específico

        $stmt = $this->conn->prepare("SELECT * FROM movies WHERE users_id = :id");
        
        // Associa o ID ao parâmetro da query
        $stmt->bindParam(":id", $id);
        
        $stmt->execute();

        $movie = [];

        foreach($stmt->fetchAll() as $data) {
            $movie[] = $this->buildMovie($data);
        }

        return $movie;
    }

    public function findById($id) {
        // Retorna um único filme pelo ID

        $stmt = $this->conn->prepare("SELECT * FROM movies WHERE id = :id");
        
        $stmt->bindParam(":id", $id);
        
        $stmt->execute();

        // Verifica se encontrou algum registro
        if($stmt->rowCount() > 0) {
            // Converte o primeiro resultado em objeto Movie e retorna
            return $this->buildMovie($stmt->fetch());
        }

        // Se não encontrar, retorna false
        return false;
    }

    public function findByTitle($title) {
        // Retorna filmes cujo título seja exatamente igual ao informado

        $stmt = $this->conn->prepare("SELECT * FROM movies WHERE title = :title");
        
        $stmt->bindParam(":title", $title);
        
        $stmt->execute();

        $movie = [];

        // Verifica se encontrou registros
        if($stmt->rowCount() > 0) {
            
            $data = $stmt->fetchAll();
            
            // Converte cada registro em objeto Movie
            foreach($data as $item) {
                $movie[] = $this->buildMovie($item);
            }
        }

        return $movie;
    }

    public function create(Movie $movie) {
        // Adiciona um novo filme no banco

        $stmt = $this->conn->prepare("
            INSERT INTO movies (title, description, image, trailer, category, length, users_id)
            VALUES (:title, :description, :image, :trailer, :category, :length, :users_id)");

        // Associa os valores do objeto Movie aos parâmetros da query
        $stmt->bindParam(":title", $movie->title);
        $stmt->bindParam(":description", $movie->description);
        $stmt->bindParam(":image", $movie->image);
        $stmt->bindParam(":trailer", $movie->trailer);
        $stmt->bindParam(":category", $movie->category);
        $stmt->bindParam(":length", $movie->length);
        $stmt->bindParam(":users_id", $movie->users_id);
        
        // Executa a inserção
        $stmt->execute();
    }

    public function update(Movie $movie) {
        // Atualiza os dados de um filme existente

        $stmt = $this->conn->prepare("
            UPDATE movies SET 
                title = :title,
                description = :description,
                image = :image,
                trailer = :trailer,
                category = :category,
                length = :length
            WHERE id = :id ");

        // Associa os novos valores
        $stmt->bindParam(":title", $movie->title);
        $stmt->bindParam(":description", $movie->description);
        $stmt->bindParam(":image", $movie->image);
        $stmt->bindParam(":trailer", $movie->trailer);
        $stmt->bindParam(":category", $movie->category);
        $stmt->bindParam(":length", $movie->length);
        $stmt->bindParam(":id", $movie->id);

        // Executa a atualização
        $stmt->execute();
    }

    public function destroy($id) {
        // Remove um filme do sistema pelo ID

        $stmt = $this->conn->prepare("DELETE FROM movies WHERE id = :id");
        
        $stmt->bindParam(":id", $id);
        
        $stmt->execute();
    }
}
