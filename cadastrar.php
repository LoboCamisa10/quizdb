<?php 
    include("database.php");

    if (isset($_POST["cadastrar"])){
        if (!empty($_POST["username"]) && !empty($_POST["password"])){

            function validate($data){
                $data = trim($data); // Tira os espaços do inicio/fim
                $data = stripslashes($data); //retira contra barras duplas
                $data = htmlspecialchars($data); // converte charactere especiais em entidades html
                return $data;
            }

            $nome = validate($_POST["name"]);
            $idade = validate($_POST["idade"]);
            $nickname = validate($_POST["username"]) ;
            $password = validate($_POST["password"]) ;
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (nome, idade, login, password) VALUES ('$nome', '$idade', '$nickname', '$hash')";
        }

        try{
            mysqli_query($connection, $sql);
            echo "Usuário está cadastrado. <br>";

            header("Location: index.php?registered=Usuário registrado !");
            exit();
        }

        catch(mysqli_sql_exception){
            echo "Usuário não cadastrado. <br>";
        }
    }

    mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TriGlobal Cadastre-se</title>
    <link rel="stylesheet" href="styles/style-cadastrar.css">
</head>

<body>
    <h1>
        Coloque as informações abaixo para cadastrar ↓
    </h1>

    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <label for="username">Nome:</label><br>
        <input type="text" name="name"><br>

        <label for="username">Idade:</label><br>
        <input type="number" name="idade"><br>

        <label for="username">Login:</label><br>
        <input type="text" name="username"><br>

        <label for="username">Senha:</label><br>
        <input type="password" name="password"><br>

        <input type="submit" name="cadastrar" id="cadastrar" value="Cadastrar">

    </form>

</body>
</html>
