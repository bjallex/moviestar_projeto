<?php

require_once(__DIR__ . "/../models/Movie.php");

class MovieDAO {

    private $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function buildMovie($data) {
        $movie = new Movie();
        $movie->id = $data["id"];
        $movie->title = $data["title"];
        $movie->description = $data["description"];
        $movie->image = $data["image"];
        $movie->trailer = $data["trailer"];
        $movie->category = $data["category"];
        $movie->length = $data["length"];
        $movie->users_id = $data["users_id"];
        return $movie;
    }

    public function findAll() {
        // Retorna todos os filmes
        $stmt = $this->conn->query("SELECT * FROM movies ");
        $movie=[];
        
         foreach($stmt->fetchAll() as $data) {
         $movie[] = $this->buildMovie($data);
        }

        return $movie;
    }

    public function getLatestMovies() {
        // Retorna os filmes mais recentes
        $stmt = $this->conn->query("SELECT * FROM movies ORDER BY id DESC LIMIT 10");
        $movie = [];

        foreach($stmt->fetchAll() as $data) {
            $movie[] = $this->buildMovie($data);
        }

        return $movie;
    }

    public function getMoviesByCategory($category) {
        // Retorna filmes filtrados por categoria
          $stmt = $this->conn->prepare("SELECT * FROM movies WHERE category = :category");
            $stmt->bindParam(":category", $category);
            $stmt->execute();

            $movie = [];
            foreach($stmt->fetchAll() as $data) {
                $movie[] = $this->buildMovie($data);
            }

            return $movie;
    }

    public function getMoviesByUserId($id) {
        // Retorna filmes cadastrados por um usuário específico
         $stmt = $this->conn->prepare("SELECT * FROM movies WHERE users_id = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            $movie = [];
            foreach($stmt->fetchAll() as $data) {
                $movie[] = $this->buildMovie($data);
            }

            return $movie;
    }

    public function findById($id) {
        // Retorna um filme pelo ID
            $stmt = $this->conn->prepare("SELECT * FROM movies WHERE id = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                return $this->buildMovie($stmt->fetch());
            }

            return false;
    }

    public function findByTitle($title) {
        // Retorna filmes cujo título contenha a string informada
       
            $stmt = $this->conn->prepare("SELECT * FROM movies WHERE title = :title");
            $stmt->bindParam(":title", $title);
            $stmt->execute();

            $movie = [];

            if($stmt->rowCount() > 0) {
                $data = $stmt->fetchAll();
                foreach($data as $item) {
                    $movie[] = $this->buildMovie($item);
                }
            }

            return $movie;
    }

    public function create(Movie $movie) {
        // Adiciona um novo filme
         $stmt = $this->conn->prepare("
            INSERT INTO movies (title, description, image, trailer, category, length, users_id)
            VALUES (:title, :description, :image, :trailer, :category, :length, :users_id)");

            $stmt->bindParam(":title", $movie->title);
            $stmt->bindParam(":description", $movie->description);
            $stmt->bindParam(":image", $movie->image);
            $stmt->bindParam(":trailer", $movie->trailer);
            $stmt->bindParam(":category", $movie->category);
            $stmt->bindParam(":length", $movie->length);
            $stmt->bindParam(":users_id", $movie->users_id);
            
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

        $stmt->bindParam(":title", $movie->title);
        $stmt->bindParam(":description", $movie->description);
        $stmt->bindParam(":image", $movie->image);
        $stmt->bindParam(":trailer", $movie->trailer);
        $stmt->bindParam(":category", $movie->category);
        $stmt->bindParam(":length", $movie->length);
        $stmt->bindParam(":id", $movie->id);

        $stmt->execute();
    }

    public function destroy($id) {
        // Remove um filme do sistema
        $stmt = $this->conn->prepare("DELETE FROM movies WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
    }
}
