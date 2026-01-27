<?php

  $db_name = "moviestar";
  $db_host = "localhost";
  $db_user = "root";
  $db_pass = "";

  try{

  $conn = new PDO("mysql:host=$db_host; dbname=$db_name", $db_user, $db_pass);

  // Habilidade o lançamento de exceções para erros de SQL
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  } catch(PDOException $e){
  // Habilitar erros PDO
  $error = $e->getMessage();

  echo "Erro de conexão: " . $error;
  } 