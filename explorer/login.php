
    <form method="post">
        Informe a senha: 
        <input type="password" name="senha">
        <input type="submit" value="Enviar">
        
        <?php
            if (isset($_POST['senha'])) {
                require_once ('../config/linequest.php');

                global $CFG;
                if ($CFG->viewpass == $_POST['senha']) {
                    session_start();
                    $_SESSION['hash_user'] = md5($_POST['senha'].time());
                    header('Location: index.php');
                } else {
                    echo "<b style='color: red'>Senha incorreta!</b>";
                }
            }
            
        ?>  
    </form>