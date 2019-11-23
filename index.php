<?php

  //INDEX
    session_start();

    $sessao_user = null;

    if(isset($_SESSION['user'])){
      include 'cabecalho.php';
      echo '<div class="mensagem">
            Sr(a) <strong>'.$_SESSION['user'].'</strong> 
            já se encontra logado no site.<br><br>
            <a href="forum.php">Avançar</a>
            </div>
           ';
      include 'rodape.php';
      exit;
    }

  //CABEÇALHO
  include 'cabecalho.php';

  if($sessao_user == null){
    include 'login.php';
  }

  //RODAPÉ
  include 'rodape.php';

?>