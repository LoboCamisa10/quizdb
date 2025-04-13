<?php 
        // Preciso fazer o SANITIZE e FILTER no username e password
        // Preciso criar as 15 perguntas
        // Preciso criar 3 bancos de dados: cadastro de contas, cadastro de perguntas e respostas e cadastro de respostas do user;
        // Começar a estilizar a tela com css (caso não tenha mais nada para pensar)
        include("database.php");
        session_start();

        if(isset($_POST["login"])){
            if (!empty($_POST["username"]) && !empty($_POST["password"])){

                function validate($data){
                    $data = trim($data); // Tira os espaços do inicio/fim
                    $data = stripslashes($data); //retira contra barras duplas
                    $data = htmlspecialchars($data); // converte charactere especiais em entidades html
                    return $data;
                }

                $_SESSION["username"] = validate($_POST["username"]);
                $_SESSION["password"] = validate($_POST["password"]);

                $sqlSearch = "SELECT * FROM users WHERE login = ?";

                $stmt = mysqli_prepare($connection, $sqlSearch);
                 //cria um objeto que previni a entrada de SQL injetado
                mysqli_stmt_bind_param($stmt, "s",  $_SESSION["username"]); 
                // vincula SESSION[username] a variável stmt e informa que é "s" de string
                mysqli_stmt_execute($stmt); 
                //Executa a pequisa no banco de dados
                $result = mysqli_stmt_get_result($stmt);
                //Converte o resultado da declaração em um conjunto de resultados regular
    
                if (mysqli_num_rows($result) > 0) { // Usuário encontrados
                    while ($user = mysqli_fetch_assoc($result)){
                        print_r($user);
                        //Verifica a senha contra a hash armazenada no BD
                        if (password_verify($_SESSION["password"], $user["password"])) {
                            header("Location: question.php");
                            exit();
                        } else { // Se a senha estiver errada
                            header("Location: index.php?error=Senha incorreta !");
                            exit();
                            echo "Senha incorreta.";
                        }
                    }
                }

                else { // Usuário não encontrado.
                    header("Location: index.php?error=Usuário não encontrado !");
                    exit();
                }
            }

            else{ // Login e senha estão vazios.
                header("Location: index.php?error=Usuário e senha são necessários!");
                exit();
            }
        }

        mysqli_close($connection);
    ?>