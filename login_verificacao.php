<?php

  //Verifica os dados de login
  session_start();
  if(isset($_SESSION['user'])){
      include 'cabecalho.php';
      echo '<div class="mensagem">Já se encontra logado no site.<br><br>
            <a href="forum.php">Avançar</a>
            </div>
           ';
      include 'rodape.php';
      exit;
    }

    include 'cabecalho.php';

    $utilizador = "";
    $password_utilizador = "";

    if(isset($_POST['text_utilizador'])){
        $utilizador = $_POST['text_utilizador'];
        $password_utilizador = $_POST['text_password'];
    }

    //Verificar se os campos foram preenchidos
    if($utilizador == "" || $password_utilizador == ""){
        //ERRO - Campos não preenchidos
        echo '
               <div class="erro">
               Não foram preenchidos os campos de necessários.<br><br>
               <a href="index.php">Tente novamente.</a>
               </div>
             ';
             include 'rodape.php';
             exit;
    }

    //Verificação dos dados login
    $passwordEncriptada = md5($password_utilizador);

    include 'config.php';
    $ligacao = new PDO("mysql:dbname=$banco_dados;host=$host", $user, $password);

    $motor = $ligacao->prepare("SELECT * FROM users WHERE username = ? AND pass = ? ");
    $motor->bindParam(1, $utilizador, PDO::PARAM_STR);
    $motor->bindParam(2, $passwordEncriptada, PDO::PARAM_STR);
    $motor->execute();

    $ligacao = null;

    //Verifica se os dados correspondem a valores do banco de dados
    if($motor->rowCount() == 0){
        echo '<div class="erro">
              Dados de login inválidos.<br><br>
              <a href="index.php">Tente novamente.</a>
              </div>
             ';
             include 'rodape.php';
             exit;
    }else{
        //Definir os dados da sessão
        $_dados_user = $motor->fetch(PDO::FETCH_ASSOC);

        $_SESSION['id_user'] = $_dados_user['id_user'];
        $_SESSION['user'] = $_dados_user['username'];
        $_SESSION['avatar'] = $_dados_user['avatar'];

        echo '<div class="login_sucesso">
              Bem vindo ao fórum, <strong>'.$_SESSION['user'].'</strong>!<br><br>
              <a href="forum.php">Continuar</a>
              </div>
             ';
    }

    include 'rodape.php';

?>