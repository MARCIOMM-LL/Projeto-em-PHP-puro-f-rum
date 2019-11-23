<?php

  //FÓRUM
  session_start();
  if(!isset($_SESSION['user'])){
    include 'cabecalho.php';
    echo '<div class="erro">
          Não tem permissão para acessar a conta.<br><br>
          <a href="index.php">Retroceder</a>
          </div>
         ';
    include 'rodape.php';
    exit;
  }

  include 'cabecalho.php';

  //Dados do usuário que está logado
  echo '<div class="dados_utilizador">
        <img src="imagens/avatars/'.$_SESSION['avatar'].'">
        <span>'.$_SESSION['user'].'</span> | <a href="logout.php">Logout</a>
        </div>
       ';
  
  //Criação de um novo post
  echo '<div class="novo_post">
        <a href="editor_post.php">Novo post</a>
        </div>
       ';

  //Apresentação dos posts do fórum
  include 'config.php';
  $ligacao = new PDO("mysql:dbname=$banco_dados;host=$host", $user, $password);

  //Pegar posts da tabela posts, que estão relacionados com os usuário da tabela users
  $sql = "SELECT * FROM posts INNER JOIN users ON posts.id_user = 
          users.id_user ORDER BY data_post DESC";

  $motor = $ligacao->prepare($sql);
  $motor->execute();

  $ligacao = null;

  if($motor->rowCount() == 0){
    echo '<div class="login_sucesso">
          Não existem posts no fórum.
          </div>
         '; 
  }else{

      //Foram encontrados posts no banco de dados
      foreach($motor as $post){
      $id_post = $post['id_post'];
      $id_user = $post['id_user'];
      $titulo = $post['titulo'];
      $mensagem = $post['mensagem'];
      $data_post = $post['data_post'];

      //Dados do usuário
      $username = $post['username'];
      $avatar = $post['avatar'];

      echo '<div class="post">';
            
      //Dados do user
      echo '<img src="imagens/avatars/'.$avatar.'">';
      echo '<span id="post_username">'.$username.'</span>';
      echo '<span id="post_titulo">'.$titulo.'</span>';
      echo '<hr>';
      echo '<div id="post_mensagem">'.$mensagem.'</div>';

      //Apresentação da data e hora da mensagem do post
      echo '<div id="post_data">';

      //Adicionar o link editar para usuário que está logado 
      if($id_user == $_SESSION['id_user']){
        echo '<a href="editor_post.php?pid='.$id_post.'" id="editar">Editar</a>';
      }

      echo $data_post;

      echo '<span id="id_post">#'.$id_post.'</span>';

      echo '</div></div>';

    }
  }

  //Pegar dados da tabela users
  $ligacao = new PDO("mysql:dbname=$banco_dados;host=$host", $user, $password);

  $motor = $ligacao->prepare("SELECT id_user FROM users");
  $motor->execute();

  $numUsers = $motor->rowCount();
  if($numUsers == null){
    $numUsers = 0;
  }
  
 //Pegar dados da tabela posts
  $motor = $ligacao->prepare("SELECT id_post FROM posts");
  $motor->execute();

  $numPosts = $motor->rowCount();
  if($numPosts == null){
    $numPosts = 0;
  }

  $ligacao = null;

  //Apresentar os dados: número de users registrados e número de posts gravados
  //no banco de dados
  echo '
        <div class="totais">
        Número de usuários registrados: <strong>'.$numUsers.'</strong><br><br>
        Número total de posts: <strong>'.$numPosts.'</strong>
        </div>
       ';

  include 'rodape.php';

?>