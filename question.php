<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perguntas Trívia</title>
    <link rel="stylesheet" href="styles/style-questions.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <?php
                // Iniciar sessão e incluir conexão com o banco de dados
                session_start();
                require_once 'database.php'; // Caminho para o banco de dados uma única vez.

                // Checa se o usuário está logado, caso não. Volta pro index.
                if (!isset($_SESSION["username"])) {
                    header("Location: index.php");
                    exit();
                }

                // stmt significa statement (especificamente, uma declaração preparada)
                // É um objeto retornado por mysqli_prepare() que representa:
                // Uma versão pré-compilada da sua consulta SQL
                // Um modelo que pode ser executado eficientemente várias vezes com parâmetros diferentes
                // Uma maneira segura de executar consultas com parâmetros
                
                $sqlSearch = "SELECT * FROM users WHERE login = ?";
                $stmt = mysqli_prepare($connection, $sqlSearch);

                if (!$stmt) {
                    die("Prepare failed: " . mysqli_error($connection));
                    return;
                }

                mysqli_stmt_bind_param($stmt, "s", $_SESSION["username"]); 
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) > 0) { 
                    $user = mysqli_fetch_assoc($result);
                    $name = $user["nome"] ?? 'Usuário';
                    $age = $user["idade"] ?? '0';
                } else {
                    header("Location: index.php?error=Usuário não encontrado !");
                    exit();
                }
            ?>

            <div>
                <ul>
                    <li id="photo"><img src="images/dog-profile.jpg" alt="profile-photo" width="160"></li>

                    <li class="profile-info">
                        <div class="profile-name"><?php echo htmlspecialchars($name); ?></div>
                        <div class="profile-age"><?php echo htmlspecialchars($age); ?> ano(s)</div>
                    </li>
                </ul>
            </div>

            <div class="answered-counter">
                <div class="counter-number" id="answered-count">0</div>
                <div class="counter-label">Perguntas Respondidas</div>
            </div>
        </nav>
    </header>

    <main>
        <div>
            Tarefas para serem feitas: <br>
            - Criar uma barra de navegação com imagem, nome, idade e as perguntas respondidas.  FEITO ✅<br>
            - Criar as abas de perguntas, e colocar duas perguntas lá, com form e radio. FEITO ✅ <br>
            - Fazer os testes no banco de dados, como tentar armazenar as respostas e fazer a correção dessas duas perguntas. <br>
            - Colocar um sistema para atualizar a foto do Perfil <br>
            - Colocar as perguntas em verde ou amarelo dependendo se a pergunta estiver certa ou errada <br>
            - Colocar o botão de logout dentro da barra de navegação
        </div>
                
        <h1>VOCÊ ESTÁ ON !</h1>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
            <input type="submit" name="logout" value="logout">
        </form>
        <?php 
            if(isset($_POST["logout"])){
                session_destroy();
                header("Location: index.php");
            }
        ?>

        <form action= "<?php $_SERVER['PHP_SELF'] ?>" method="get">
            <label for="question_1" class="question">1 - Qual NPC está relacionado as Daimons? </label> <br>
            <input type="radio" name="resposta_1" id="resposta_1" class="answer" value="Senhor Zhenjun" >Senhor Zhenjun <br>
            <input type="radio" name="resposta_1" id="resposta_1" class="answer" value="Emissário Elemental">Emissário Elemental <br>
            <input type="radio" name="resposta_1" id="resposta_1" class="answer" value="Deus Antigo">Deus Antigo <br>

            <label for="question_2" class="question">2 - Que NPC é necessário quando os personagens querem casar?</label><br>
            <input type="radio" name="resposta_2" id="resposta_2" class="answer" value="Casamenteiro Antonio">Casamenteiro Antonio<br>
            <input type="radio" name="resposta_2" id="resposta_2" class="answer" value="Cupido">Cupido <br>
            <input type="radio" name="resposta_2" id="resposta_2" class="answer" value="Ancião da Cidade do Dragão">Ancião da Cidade do Dragão <br>
            
            <input type="submit" name="EnviarRespostas" value="Enviar respostas">
        </form>
    </main>

    <?php 
        $correctAnswers = [
            'resposta_1' => 'Emissário Elemental',
            'resposta_2' => 'Cupido'
        ];
        
        $results = []; // Array para verificar se a resposta está correta usando 1 para true com if
        $userAnswers = [];

    
        if (isset($_GET["EnviarRespostas"])){
            
            foreach($correctAnswers as $answer => $correctAnswer){

                if (isset($_GET[$answer])) {
                    $userAnswer = $_GET[$answer];
                    $userAnswers[$answer] = $userAnswer;
                    $results[$answer] = ($userAnswer === $correctAnswer);
                } else {
                    $userAnswers[$answer] = null;
                    $results[$answer] = false;
                }
                print_r($userAnswers);

                // Mostra na tela o resultado das perguntas
                echo "<p>Pergunta " . substr($answer, -1) . ": ";

                //Verifica se a resposta não está vazia
                if ($userAnswers[$answer] === null){
                    echo"Nenhuma resposta selecionada.";
                } else
                {
                    echo $results[$answer] 
                    ? "Correta a resposta é $userAnswer !" 
                    : "Incorreto. Você respondeu '$userAnswer', o correto é '$correctAnswer'";
                    echo "</p>";
                }
            }

        }else{
            foreach( $correctAnswers as $answer => $correctAnswer){
                echo "<p>Pergunta " . substr($answer, -1) . ": Nenhuma resposta selecionada.</p>";
            }
        }

        // Calculate score
        $score = count(array_filter($results));
        $total = count($correctAnswers);
        echo "<p>Você acertou $score de $total perguntas.</p>";
    ?>
</body>
</html>

