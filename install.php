<?php

  //INSTALL
  //Criar o banco de dados que suporta o site

  //Importação das variáveis que são inseridas dentro 
  //dos parâmetros do objeto new pdo
  include 'config.php';

   
  //Conexção com o banco de dados
  $ligacao = new PDO("mysql:host=$host", $user, $password);

  //Abrir conexão com o banco de dados 
  $motor = $ligacao->prepare("CREATE DATABASE $banco_dados");
  $motor->execute();

  //Fecho da conexão com o banco de dados
  $ligacao = null;
  
  echo '<p>Banco de dados criado com sucesso.</p><hr>';

  //Ligação com o banco de dados para a criação das tabelas 
  $ligacao = new PDO("mysql:dbname=$banco_dados;host=$host", $user, $password);

  //Criação da tabela users
  $sql = "CREATE TABLE users(
                              id_user    INT NOT NULL PRIMARY KEY,
                              username   VARCHAR(30),
                              pass       VARCHAR(100),
                              avatar     VARCHAR(250)
                            )";

  $motor = $ligacao->prepare($sql);
  $motor->execute();

  echo '<p>Tabela users criada com sucesso.</p>';

  //Criação da tabela posts
  $sql = "CREATE TABLE posts(
                              id_post    INT NOT NULL PRIMARY KEY,
                              id_user    INT NOT NULL,
                              titulo     VARCHAR(100),
                              mensagem   TEXT,
                              data_post  DATETIME,
                              FOREIGN KEY(id_user) REFERENCES users(id_user) ON DELETE CASCADE
                            )";

  $motor = $ligacao->prepare($sql);
  $motor->execute();

  //Fecho da conexão com o banco de dados
  $ligacao = null;

  echo '<p>Tabela posts criada com sucesso.</p>';
  echo '<hr>';
  echo '<p>Processo de criação do bando de dados terminado com sucesso.</p>';

?>