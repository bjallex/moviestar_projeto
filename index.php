<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

  // Incluir o cabeçalho
  require_once("templates/header.php");

  // Incluir o DAO dos filmes
  require_once("dao/MovieDAO.php");

  // Incluir as  Messagens 
  require_once("modeles/Message.php");

  // Criar o objeto DAO para acessar os filmes
  $movieDao = new MovieDAO($conn, $BASE_URL);

  

  // Buscar os filmes mais recentes
  $latestMovies = $movieDao->getLatestMovies() ?? []; /// Operador de Coalescencia nula

  // Buscar filmes de categorias específicas
  $actionMovies = $movieDao->getMoviesByCategory("Ação") ?? [];
  $comedyMovies = $movieDao->getMoviesByCategory("Comédia") ?? [];
?>
<div id="main-container" class="container-fluid">

  <!-- Seção: Filmes novos -->
  <h2 class="section-title">Filmes novos</h2>
  <p class="section-description">Veja as críticas dos últimos filmes adicionados no MovieStar</p>
  <div class="movies-container">
    <?php foreach($latestMovies ?? [] as $movie): ?>
      <?php 
        // Exibir cada filme usando o template movie_card.php
        require("templates/movie_card.php"); 
      ?>
    <?php endforeach; ?>
    <?php if(count($latestMovies) === 0): ?>
      <p class="empty-list">Ainda não há filmes cadastrados!</p>
    <?php endif; ?>
  </div>

  <!-- Seção: Ação -->
  <h2 class="section-title">Ação</h2>
  <p class="section-description">Veja os melhores filmes de ação</p>
  <div class="movies-container">
    <?php foreach($actionMovies as $movie): ?>
      <?php require("templates/movie_card.php"); ?>
    <?php endforeach; ?>
    <?php if(count($actionMovies) === 0): ?>
      <p class="empty-list">Ainda não há filmes de ação cadastrados!</p>
    <?php endif; ?>
  </div>

  <!-- Seção: Comédia -->
  <h2 class="section-title">Comédia</h2>
  <p class="section-description">Veja os melhores filmes de comédia</p>
  <div class="movies-container">
    <?php foreach($comedyMovies as $movie): ?>
      <?php require("templates/movie_card.php"); ?>
    <?php endforeach; ?>
    <?php if(count($comedyMovies) === 0): ?>
      <p class="empty-list">Ainda não há filmes de comédia cadastrados!</p>
    <?php endif; ?>
  </div>

</div>
<?php
  // Incluir o rodapé
  require_once("templates/footer.php");
?>
