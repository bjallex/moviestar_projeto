
<?php
// Verifica se usuário está autenticado
require_once("globals.php");
require_once("db.php");

require_once("models/User.php");
require_once("models/Movie.php");
require_once("models/Message.php");

require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");

$message = new Message($BASE_URL);
$userDao = new UserDAO($conn, $BASE_URL, $message);



$movieDao = new MovieDAO($conn);

// PROCESSA O FORMULÁRIO
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $movie = new Movie();

    $movie->title = $_POST["title"];
    $movie->description = $_POST["description"];
    $movie->category = $_POST["category"];
    $movie->trailer = $_POST["trailer"];
    $movie->length = $_POST["length"];
    $movie->users_id = $userData->id;

    $movie->image = "movie_cover.jpg";

    $movieDao->create($movie);

    $message->setMessage("Filme adicionado com sucesso!", "success", "index.php");
}

require_once("templates/header.php");
?>


  <div id="main-container" class="container-fluid">
    <div class="offset-md-4 col-md-4 new-movie-container">
      <h1 class="page-title">Adicionar Filme</h1>
      <p class="page-description">Adicione sua crítica e compartilhe com o mundo!</p>
      <form action="" id="add-movie-form" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="type" value="create">
        <div class="form-group">
          <label for="title">Título:</label>
          <input type="text" class="form-control" id="title" name="title" placeholder="Digite o título do seu filme">
        </div>
        <div class="form-group">
          <label for="image">Imagem:</label>
          <input type="file" class="form-control-file" name="image" id="image">
        </div>
        <div class="form-group">
          <label for="length">Duração:</label>
          <input type="text" class="form-control" id="length" name="length" placeholder="Digite a duração do filme">
        </div>
        <div class="form-group">
          <label for="category">Category:</label>
          <select name="category" id="category" class="form-control">
              <option value="Ação" disabled selected>Selecionar</option>
              <option value="Ação">Ação</option>
              <option value="Comedia">Comedia</option>
              <option value="Terror">Terror</option>
              <option value="Romance">Romance</option>
          </select>
        </div>
        <div class="form-group">
          <label for="trailer">Trailer:</label>
          <input type="text" class="form-control" id="trailer" name="trailer" placeholder="Insira o link do trailer">
        </div>
        <div class="form-group">
          <label for="description">Descrição:</label>
          <textarea name="description" id="description" rows="5" class="form-control" placeholder="Descreva o filme..."></textarea>
        </div>
        <input type="submit" class="btn card-btn" value="Adicionar filme">
      </form>
    </div>
  </div>
<?php
  require_once("templates/footer.php");
?>