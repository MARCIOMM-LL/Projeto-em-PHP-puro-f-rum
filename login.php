<?php

  //LOGIN
  echo '
         <form class="form_login" method="post" action="login_verificacao.php">
         <h3>Login</h3><hr><br>
         Para entrar no micro fórum, necessita introduzir o seu username e password.<br>
         Se não tem conta de usuário, pode criar uma <a href="signup.php">aqui.</a><br><br>
         Username:<br><input type="text" size="20" name="text_utilizador"><br><br>
         Password:<br><input type="password" size="20" name="text_password"><br><br>
         <input type="submit" name="btn_submit" value="Entrar">
         </form>
       ';

?>