<?php 
    include("database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trivia Filmes</title>
    <link rel="stylesheet" href="styles/style-index.css">
</head>

<body>

    <div class="header-container">
        <h1>Bem-vindo(a) à TriGlobal</h1>
        <img src="images/icon-body.png" alt="Site Logo" width="80">
    </div>

    <div class="login-body">
        <div class="register-forms">
            <form action="login.php" method="post">
                <h2> LOGIN </h2>

                <?php
                    if(isset($_GET["error"])) { ?>
                        <p class="error"><?php echo $_GET["error"] ?> </p>
                <?php    }?>

                <?php 
                    if(isset($_GET["registered"])) { ?>
                    <p class="registered"><?php echo $_GET["registered"] ?> </p>
                <?php }?>

                <label for="username">Usuário</label><br>
                <input type="text" name="username" placeholder="Login"><br>
        
                <label for="username">Senha</label><br>
                <input type="password" name="password" placeholder="Password"><br>

                <!-- <input type="submit" name="login" id="login" value="Login"> -->
                <button type="submit" name="login">Login</button><br>
                
                <!-- <p class="cadastro">
                    Não tem conta ?
                    <a href = "cadastrar.php">Criar uma conta</a>
                </p> -->
            
            </form>

            <form action="cadastrar.php" method="get">
                <div class="cadastro">
                    <p id="create-count-text">Não tem conta?</p>
                    <button type="submit" class="register-btn">
                        Criar uma conta
                    </button>
                </div>
            </form>
        </div>
    </div>



</body>
</html>
