<?php 
    include("database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trivia Filmes</title>
</head>

<body>
    <h1>
        Trívia de Filmes
    </h1>

    <h2>
        LOGIN ↓
    </h2>

    <form action="login.php" method="post">

        <?php 
            if(isset($_GET["error"])) { ?>
                <p class="error"><?php echo $_GET["error"]; ?> </p>
        <?php    }?>

        <?php 
            if(isset($_GET["registered"])) { ?>
            <p class="registered"><?php echo $_GET["registered"]; ?> </p>
        <?php }?>
        

        <label for="username">username:</label><br>
        <input type="text" name="username" placeholder="login"><br>
 
        <label for="username">password:</label><br>
        <input type="password" name="password" placeholder="senha"><br>

        <input type="submit" name="login" id="login" value="Login">
    </form>
    <p>Não tem cadastro? </p>
    <a href = "cadastrar.php">Cadastrar-se</a>

</body>
</html>
