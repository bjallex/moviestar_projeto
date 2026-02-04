<?php

  require_once(__DIR__ . "/../globals.php");
  require_once(__DIR__ . "/../db.php");
  require_once(__DIR__ . "/../models/Message.php");
  require_once(__DIR__ . "/../dao/UserDAO.php");

  $message = new Message($BASE_URL);

  $flassMessage = $message->getMessage();

  if(!empty($flassMessage["msg"])) {
    // Limpar a mensagem
    $message->clearMessage();
  }

  $userDao = new UserDAO($conn, $BASE_URL, $message);

  $userData = $userDao->verifyToken(false);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MovieStar</title>
  <link rel="short icon" href="<?= $BASE_URL ?>img/moviestar.ico" />
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.css" integrity="sha512-drnvWxqfgcU6sLzAJttJv7LKdjWn0nxWCSbEAtxJ/YYaZMyoNLovG7lPqZRdhgL1gAUfa+V7tbin8y+2llC1cw==" crossorigin="anonymous" /><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
  <!-- CSS do projeto -->
  <link rel="stylesheet" href="<?= $BASE_URL ?>css/styles.css">
</head>
<body>
  <header>
  <nav id="main-navbar">
    <a href="index.php" class="navbar-brand">
      <img src="img/logo.svg" alt="MovieStar" id="logo">
      <span id="moviestar-title">MovieStar</span>
    </a>
    <!-- BOTÃO SEARCH -->
    <form action="#" method="GET" id="search-form">
      <input type="text" name="q" id="search" placeholder="Buscar Filmes" aria-label="Search">
      <button type="submit"><i class="bi bi-search"></i></button>
    </form> <!--vericação de usuario -->
    <?php if($userData): ?> <!--usando variavel if para verificar a existência de cadastro de usuário--> 
    <ul class="navbar-nav">
      <li class="nav-item">
        <a href="<?= $BASE_URL ?>newmovie.php" class="nav-link"><i class="bi bi-plus-square"></i>Incluir Filme</a>
      </li>
      <li class="nav-item">
        <a href="<?= $BASE_URL ?>dashboard.php" class="nav-link">Meus Filmes</a>
      </li>
      <li class="nav-item">
        <a href="<?= $BASE_URL ?>editprofile.php" class="nav-link bold"><?= $userData->name?></a>
      </li>
      <li class="nav-item">
        <a href="<?= $BASE_URL ?>logout.php" class="nav-link">Sair</a>
      </li>
         <?php else: ?>
      <li class="nav-item">
        <a href="<?= $BASE_URL ?>auth.php" class="nav-link">Entrar / Cadastrar</a>
      </li>
      <?php endif; ?>
    </ul>
  </nav>
  
</header>

<style>
  * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: Arial, Helvetica, sans-serif;
}

/* BODY */
body {
  background-color: #000;
  color: #fff;
}

/* NAVBAR */
#main-navbar {
  background-color: #111;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 30px;
  border-bottom: 1px solid #222;
  height: 60px;
}

/* LOGO */
.navbar-brand {
  display: flex;
  align-items: center;
  text-decoration: none;
}

#logo {
  width: 40px;
  margin-right: 10px;
}

#moviestar-title {
  font-size: 22px;
  font-weight: bold;
  color: #fff;  
}

/* BUSCA */
#search-form {
  display: flex;
  align-items: center;
  width: 50%;
}

#search {
  width: 100%;
  height: 40px; 
  padding: 10px 14px;
  border-radius: 4px 0 0 4px;
  border: none;
  outline: none;
  font-size: 14px;
}

#search-form button {
  background-color: #f7f7f7;
  height: 40px;
  border: none;
  padding: 10px 14px;
  border-radius: 0 4px 4px 0;
  cursor: pointer;
}
#search-form :hover {
  background-color: #f1f1f1;
}
/* MENU */
.navbar-nav {
  list-style: none;
  display: flex;
  flex-direction: inherit;
}

.nav-item {
  margin-left: 18px;
  display: inline-block;
}

.nav-link {
  color: #ddd;
  text-decoration: none;
  font-size: clamp(0.5rem, 1.2vw, 1rem);
  transition: 0.3s;
}

.nav-link:hover, .nav-link.bold:hover {
  color: #f5c518;
}

.nav-link.bold {
  font-weight: bold;
  color: #fff;
}
</style>