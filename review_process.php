<?php

// TODO: Incluir arquivos necessários (globals, db, models, DAOs)

require_once("globals.php");
require_once("db.php");
require_once("models/Review.php");
require_once("models/Message.php");
require_once("models/Movie.php");
require_once("dao/UserDAO.php");
require_once("dao/ReviewDAO.php");
require_once("dao/MovieDAO.php");

// TODO: Criar instâncias das classes necessárias EX:
$message = new Message($BASE_URL);
$userDao = new UserDAO($conn, $BASE_URL);
$reviewDao = new ReviewDao($conn);
$movieDao = new MovieDAO($conn);

// TODO: Receber o tipo do formulário enviado pelo POST
$type = filter_input(INPUT_POST, "type"); // Importante frisar que podemos usar ela com varios tipos do input e seus nomes

// TODO: Resgatar dados do usuário logado
$userData = $userDao->verifyToken();

// TODO: Criar condicional para verificar se o formulário é de criação de review
if($type === "create") {

    // TODO: Receber os dados enviados pelo POST: rating, review, movies_id
      $rating = filter_input(INPUT_POST, "rating");
      $review = filter_input(INPUT_POST, "review");
      $movies_id = filter_input(INPUT_POST, "movies_id");

    // TODO: Criar condicional para validar se todos os campos obrigatórios foram preenchidos
    // Se algum campo estiver vazio, mostrar uma mensagem de erro

    // TODO: Criar condicional para verificar se o filme existe no sistema
    // $movieData = $movieDao->findById($movies_id);
    // Se não existir, mostrar mensagem de erro

    // TODO: Criar objeto Review (ou array) e preencher com os dados recebidos

    // TODO: Salvar a review no sistema (simulação ou print_r)

    // TODO: Mensagem de sucesso (simulada)
    
} else {

    // TODO: Criar condicional para casos de type inválido
    // Mostrar mensagem de erro

}
