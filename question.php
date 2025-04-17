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
                    print_r($user);
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

        <form action= "<?php $_SERVER['PHP_SELF'] ?>" method="post">
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
    //A FAZER
    //1)   Preciso colocar as respostas que foram marcadas e estão no banco de dados
    //como a única respostas que vão mostrar no site
    //2)   Preciso criar um botão para "resetar" o questionário e deletar as informações
    //salvas no banco de dados.
    //3)   Preciso colocar todas as perguntas corretas dentro do banco de dados
    //4)   Preciso atualizar as respostas respondidas acima com variáveis
    //5)   Preciso deixar esse código mais bonito, organizado e comentado.
        // Run this once to populate questions
        $questions = [
            [
                'question_text' => 'Qual NPC está relacionado as Daimons?',
                'correct_answer' => 'Emissário Elemental'
            ],
            [
                'question_text' => 'Que NPC é necessário quando os personagens querem casar?',
                'correct_answer' => 'Cupido'
            ]
        ];

        // Insert questions only if they don't exist

        // foreach ($questions as $question) {
        //     $stmt = $connection->prepare("INSERT INTO questions (question_text, correct_answer) VALUES (?, ?)");
        //     $stmt->bind_param("ss", $question['question_text'], $question['correct_answer']);
        //     $stmt->execute();
        // }

        $correctAnswers = [
            'resposta_1' => 'Emissário Elemental',
            'resposta_2' => 'Cupido'
        ];
        
        $results = []; // Array para verificar se a resposta está correta usando 1 para true com if
        $userAnswers = [];
    
        if (isset($_POST["EnviarRespostas"])){

            // Get user ID from session
            $userId = $user['id']; // Assuming your users table has an 'id' column
            
            $allAnswered = true;
            $score = 0;
            
            foreach($correctAnswers as $answer => $correctAnswer){
                $questionId = substr($answer, -1); // Gets question number (1, 2)

                if (isset($_POST[$answer])) {
                    $userAnswer = $_POST[$answer];
                    $userAnswers[$answer] = $userAnswer;
                    $isCorrect = ($userAnswer === $correctAnswer);
                    $results[$answer] = ($userAnswer === $correctAnswer);

                    // First check if answer already exists
                    $checkStmt = $connection->prepare("SELECT id FROM answers WHERE user_id = ? AND question_id = ?");
                    $checkStmt->bind_param("ii", $userId, $questionId);
                    $checkStmt->execute();
                    $checkStmt->store_result();

                    //If tiver respostas vazias, inseri a resposta do usuário no banco
                    if ($checkStmt->num_rows === 0){
                        $stmt = $connection->prepare("INSERT INTO answers (user_id, question_id, user_answer, is_correct) VALUES (?, ?, ?, ?)");

                        $stmt->bind_param("iisi", $userId, $questionId, $userAnswer, $isCorrect);
                        $stmt->execute();

                        if (!$stmt) {
                            die("Prepare failed: " . $connection->error);
                        }
                        if (!$stmt->bind_param(...)) {
                            die("Binding failed: " . $stmt->error);
                        }
                        if (!$stmt->execute()) {
                            die("Execute failed: " . $stmt->error);
                        }
                    }

                    if ($isCorrect) {
                        $score++;
                    }

                } else {
                    $userAnswers[$answer] = null;
                    $results[$answer] = false;
                    $allAnswered = false;
                }

                // Redirect immediately after processing
                // header("Location: ".$_SERVER['PHP_SELF'] . "?submitted=true");
                // exit();

                if ($allAnswered){

                    // Get all questions and answers for display
                    $questionsResult = $connection->query("SELECT * FROM questions ORDER BY id");
                    $answersResult = $connection->query("SELECT * FROM answers 
                                                    WHERE user_id = $userId 
                                                    ORDER BY question_id DESC 
                                                    LIMIT " . count($correctAnswers));
                    
                    echo "<div class='results-container'>";
                    echo "<h2>Seu Resultado: $score/" . count($correctAnswers) . "</h2>";
                    echo "</div>";
                } elseif (isset($_POST["EnviarRespostas"])) {
                    echo "<p>Por favor, responda todas as perguntas antes de enviar.</p>";
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


