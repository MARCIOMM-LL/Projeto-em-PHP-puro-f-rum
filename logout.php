<?php

  //Logout
  session_start();

  include 'cabecalho.php';
 
  $mensagem = "Página não disponível a visitantes.";
  
  if(isset($_SESSION['user'])){ 
      $mensagem = 'Até à próxima, '.$_SESSION['user'].'!';
  }

  //Logout do usuário
  unset($_SESSION['user']);

  //Apresenta a box com a mensagem até à próxima
  echo ' 
        <div class="login_sucesso">'.$mensagem.'<br><br>
        <a href="index.php">Início</a>
        </div>
       ';

  include 'rodape.php';

?>