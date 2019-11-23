<?php

  //Grava ou edita o post
  session_start();
  if(!isset($_SESSION['user'])){
    include 'cabecalho.php';
    echo '<div class="erro">
          Não tem permissão para ver o conteúdo da página.<br><br>
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

  //Pegar dados do formulário
  $id_user = $_POST['id_user'];
  $id_post = $_POST['id_post'];
  $titulo = $_POST['text_titulo'];
  $mensagem = $_POST['text_mensagem'];

  $editar = false;

  //Verificar se os campos estão preenchidos
  if($titulo == "" || $mensagem == ""){

    //ERRO - Campos não preenchidos
    echo '<div class="erro">
          Não foram preenchidos os campos de necessários.<br><br>
          <a href="editor_post.php">Tente novamente.</a>
          </div>
         ';
         include 'rodape.php';
         exit;
  } 

  //Abrir ligação com o banco de dados
  include 'config.php';
  $ligacao = new PDO("mysql:dbname=$banco_dados;host=$host", $user, $password);

  //Criação de novo post
  if($id_post == -1){

    //Vai pegar o id_post disponível
    $motor = $ligacao->prepare("SELECT MAX(id_post) AS MaxID FROM posts");
    $motor->execute();
    $id_post = $motor->fetch(PDO::FETCH_ASSOC)['MaxID'];

    if($id_post == null){
      $id_post = 0;
    }else{
      $id_post++;

      $editar = false;
    }

    }else{
      $editar = true;
    }

    //Criar um novo post
    if(!$editar){

      //Data atual 
      $data = date('Y-m-d H:i:s', time());

      $motor = $ligacao->prepare("INSERT INTO posts VALUES(?,?,?,?,?)");
      $motor->bindParam(1, $id_post, PDO::PARAM_INT);
      $motor->bindParam(2, $id_user, PDO::PARAM_INT);
      $motor->bindParam(3, $titulo, PDO::PARAM_STR);
      $motor->bindParam(4, $mensagem, PDO::PARAM_STR);
      $motor->bindParam(5, $data, PDO::PARAM_STR);
      $motor->execute();
    }else{

      //Editar os dados do post escolhido no banco de dados 
      $data = date('Y-m-d H:i:s', time()); 

      $motor = $ligacao->prepare("UPDATE posts 
                                 SET titulo = :tit,
                                 mensagem = :mess, 
                                 data_post = :dat
                                 WHERE 
                                 id_post = :pid");

      $motor->bindParam(":pid",$id_post, PDO::PARAM_INT);   
      $motor->bindParam(":tit", $titulo, PDO::PARAM_STR);  
      $motor->bindParam(":mess", $mensagem, PDO::PARAM_STR);  
      $motor->bindParam(":dat", $data, PDO::PARAM_STR);      
      $motor->execute();    
     
      $ligacao = null;
   }

   //Gravado com sucesso
   echo '
         <div class="login_sucesso">
         Post gravado com sucesso.<br><br>
         <a href="forum.php">Voltar</a>
         </div>
        ';

  include 'rodape.php';

?>
