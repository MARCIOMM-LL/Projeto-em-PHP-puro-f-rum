<?php

  //SIGNUP
  session_start();

  unset($_SESSION['user']);

  //Cabeçalho
  include 'cabecalho.php';

  //Verificar se foram inseridos dados do usuário
  if(!isset($_POST['btn_submit'])){
      apresentarFormulario();
  }else{
    registrarUsuario();
  }

  //RODAPÉ
  include 'rodape.php';

  //Funções
  function apresentarFormulario(){ 

    //Apresenta o formulário para adiçionar novo usuário
    echo '
           <form class="form_signup" method="post" action="signup.php?a=signup" enctype="multipart/form-data">
              <h3>Signup</h3><hr><br>
              Username:<br><input type="text" size="20" name="text_utilizador"><br><br>
              Password:<br><input type="password" size="20" name="text_password_1"><br><br>
              Re-escrever password:<br><input type="password" size="20" name="text_password_2"><br><br>
              <input type="hidden" name="MAX_FILE_SIZE" value="50000">
              Avatar:<input type="file" name="imagem_avatar"><br>
              <small>[Imagem do tipo <strong>JPG</strong>, tamanho máximo: <strong>50Kb</strong>]</small><br><br>
              <input type="submit" name="btn_submit" value="Registrar"><br><br>
              <a href="index.php">Voltar</a>
           </form>
         '; 
  }

  function registrarUsuario(){

    //Executar as operações necessárias para o registro de um novo usuario
    $utilizador = $_POST['text_utilizador'];
    $password_1 = $_POST['text_password_1'];
    $password_2 = $_POST['text_password_2'];
    $avatar = $_FILES['imagem_avatar'];

    $erro = false;

    //Verificação de erros do usuário
    if($utilizador == "" || $password_1 == "" || $password_2 == ""){
      //ERRO - Não foram preenchidos os campos necessários
      echo '<div class="erro">Não foram preenchidos os campos necessários.</div>';
      $erro = true;
    }elseif($password_1 != $password_2){
      //ERRO - Passwords não coincidem
      echo '<div class="erro">Passwords não coincidem.</div>';
      $erro = true;
    }elseif($avatar['name'] != "" && $avatar['type'] != "image/jpeg"){
      //ERRO - Arquivo da imagem inválido
      echo '<div class="erro">Arquivo de imagem inválido.</div>';
      $erro = true;
    }elseif($avatar['name'] != "" && $avatar['size'] > $_POST['MAX_FILE_SIZE']){
      //ERRO - Arquivo de imagem maior do que o permitido
      echo '<div class="erro">Arquivo de imagem maior do que o permitido.</div>';
      $erro = true;
    }

    //Verificar se existiram erros
    if($erro){
      apresentarFormulario();
      include 'rodape.php';
      exit;
    }

    //Processamento do registro do novo usuário
    include 'config.php';
    $ligacao = new PDO("mysql:dbname=$banco_dados;host=$host", $user, $password);

    //Verificar se já existe um usuário com o mesmo username
    $motor = $ligacao->prepare("SELECT username FROM users WHERE username = ?");
    $motor->bindParam(1, $utilizador, PDO::PARAM_STR);
    $motor->execute();

    if($motor->rowCount() != 0){
      //ERRO - Usuário já se encontra registrado
      echo '<div class="erro">
            Já existe um membro do fórum com o mesmo username.
            </div>
           ';
      $ligacao = null;
      apresentarFormulario();
      include 'rodape.php';
      exit;
    }else{
      //Registo do novo usuário
      $motor = $ligacao->prepare("SELECT MAX(id_user) AS MaxID FROM users");
      $motor->execute();
      $id_temp = $motor->fetch(PDO::FETCH_ASSOC)['MaxID'];

      if($id_temp == null){
        $id_temp = 0;
      }else{
        $id_temp++;
      }
      
    }

    //Encriptar a password
    $passwordEncriptada = md5($password_1);

    $sql = "INSERT INTO users VALUES(:id_user, :user, :pass, :avatar)";
    $motor = $ligacao->prepare($sql);
    $motor->bindParam(":id_user", $id_temp, PDO::PARAM_INT); 
    $motor->bindParam(":user", $utilizador, PDO::PARAM_STR); 
    $motor->bindParam(":pass", $passwordEncriptada, PDO::PARAM_STR);
    $motor->bindParam(":avatar", $avatar['name'], PDO::PARAM_STR);
    $motor->execute();

    $ligacao = null;

    //Upload do arquivo de imagem do avatar para o servidor web
    move_uploaded_file($avatar['tmp_name'], "imagens/avatars/". $avatar['name']);

    //Apresentar uma mensagem de boas vindas ao novo usuário
    echo '
           <div class="novo_registro_sucesso">Bem vindo ao micro fórum, <strong>'.$utilizador.'</strong>!<br><br>
           A partir deste momento está em condições de fazer o seu login e participar da comunidade on-line.
           <br><br>
           <a href="index.php">Quadro de login</a>
           </div>
         ';
  }
  
?>
