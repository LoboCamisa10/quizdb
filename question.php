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

                $defaultPhoto = "images/dog-profile.jpg";
                $userPhoto = isset($_SESSION['photo']) ? $_SESSION['photo'] : $defaultPhoto;
            ?>

            <div class="nav-bar-profile">
                <ul>
                    <form action="upload.php" method="post" enctype="multipart/form-data" id="photo-form">
                        <input type="file" name="profile-photo" id="profile-photo" accept="image/*" style="display: none;" onchange="document.getElementById('photo-form').submit();">
                        
                        <label for="profile-photo" style="cursor: pointer;">
                            <img src="<?php echo htmlspecialchars($userPhoto); ?>" alt="profile-photo" width="160" style="border-radius: 50%; transition: 0.3s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                        </label>
                    </form>

                    <li class="profile-info">
                        <div class="profile-name"><?php echo htmlspecialchars($name); ?></div>
                        <div class="profile-age"><?php echo htmlspecialchars($age); ?> ano(s)</div>
                        <div class="logout">
                            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                                <input type="submit" name="logout" value="Sair da conta">
                            </form>
                            <?php 
                                if(isset($_POST["logout"])){
                                    session_destroy();
                                    header("Location: index.php");
                                    exit();
                                }
                            ?>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="answered-counter">
                <div class="counter-number" id="correct-count">
                    <?php echo isset($_SESSION['current_score']) ? $_SESSION['current_score'] : 0; ?>
                </div>
                <div class="counter-label"> / 6 Corretas</div>
            </div>
        </nav>
    </header>

    <main>
        <div>
            Tarefas para serem feitas: <br>
            - Criar uma barra de navegação com imagem, nome, idade e as perguntas respondidas.  FEITO ✅<br>
            - Criar as abas de perguntas, e colocar duas perguntas lá, com form e radio. FEITO ✅ <br>
            - Fazer os testes no banco de dados, como tentar armazenar as respostas e fazer a correção dessas duas perguntas. FEITO ✅ <br>
            - Colocar um sistema para atualizar a foto do Perfil FEITO ✅<br>
            - Colocar as respostas em verde ou vermelho dependendo se a pergunta estiver certa ou errada <br>
            - Colocar o botão de logout dentro da barra de navegação FEITO ✅
        </div>
                

        <div class="quiz-container">
            <form action= "<?php $_SERVER['PHP_SELF'] ?>" method="post">
                <label for="question_1" class="question">1 - Qual o maior orgão do corpo humano ? </label> <br>
                <input type="radio" name="resposta_1" id="resposta_1" class="answer" value="Pele" >Pele<br>
                <input type="radio" name="resposta_1" id="resposta_1" class="answer" value="Coração">Coração <br>
                <input type="radio" name="resposta_1" id="resposta_1" class="answer" value="Fígado">Fígado <br>

                <label for="question_2" class="question">2 - Qual o maior osso do corpo humano ?</label><br>
                <input type="radio" name="resposta_2" id="resposta_2" class="answer" value="Úmero">Úmero<br>
                <input type="radio" name="resposta_2" id="resposta_2" class="answer" value="Tíbia">Tíbia <br>
                <input type="radio" name="resposta_2" id="resposta_2" class="answer" value="Fêmur">Fêmur <br>

                <label for="question_3" class="question">3 - Qual o nome do hormônio responsável pelos sentimentos de recompensa e motivação, conhecido como hormônio do prazer ?</label><br>
                <input type="radio" name="resposta_3" id="resposta_3" class="answer" value="Testosterona">Testosterona<br>
                <input type="radio" name="resposta_3" id="resposta_3" class="answer" value="Melatonina">Melatonina<br>
                <input type="radio" name="resposta_3" id="resposta_3" class="answer" value="Dopamina">Dopamina <br>

                <label for="question_4" class="question">4 - Qual o nome do hormônio responsável pela resposta inflamatória do corpo, equilibrar o sistema imunológico e controlar a pressão arterial, também conhecido como hormônio do estresse ?</label><br>
                <input type="radio" name="resposta_4" id="resposta_4" class="answer" value="Progesterona">Progesterona<br>
                <input type="radio" name="resposta_4" id="resposta_4" class="answer" value="Cortisol">Cortisol <br>
                <input type="radio" name="resposta_4" id="resposta_4" class="answer" value="Ocitocina">Ocitocina <br>

                <label for="question_5" class="question">5 - Como ocorre o processo de crescimento dos músculos ? </label> <br>
                <input type="radio" name="resposta_5" id="resposta_5" class="answer" value="Primeiro ocorre pequenas lesões nas fibras musculares causadas por esforço físico e estresse mecânico que atráves de descanso são reparadas, ficando maiores e mais fortes" >Primeiro ocorre pequenas lesões nas fibras musculares causadas por esforço físico e estresse mecânico que atráves de descanso são reparadas, ficando maiores e mais fortes<br>
                <input type="radio" name="resposta_5" id="resposta_5" class="answer" value="O processo ocorre com uso de medicamentos, alto sedentarismo e boa qualidade de sono">O processo ocorre com uso de medicamentos, alto sedentarismo e boa qualidade de sono <br>
                <input type="radio" name="resposta_5" id="resposta_5" class="answer" value="O aumento muscular ocorre com a ingestão de grandes quantidades de gordura,unido a nulo estresse mecânico e esforço físico">O aumento muscular ocorre com a ingestão de grandes quantidades de gordura,unido a nulo estresse mecânico e esforço físico <br>


                <label for="question_6" class="question">6 - O que descreve melhor a sensação de vício no cerébro ?</label><br>
                <input type="radio" name="resposta_6" id="resposta_6" class="answer" value="Redução da qualidade de sono, frequência aumentada de urinar e aumento da líbido">Redução da qualidade de sono, frequência aumentada de urinar e aumento da líbido<br>
                <input type="radio" name="resposta_6" id="resposta_6" class="answer" value="A falta de vontade de ingerir alimentos com açucar somado a pouca líbido e aumento da sensação de fome">A falta de vontade de ingerir alimentos com açucar somado a pouca líbido e aumento da sensação de fome<br>
                <input type="radio" name="resposta_6" id="resposta_6" class="answer" value="É o achatamento de atividades que são prazerosas, devido ao uso constante de alguma atividade ou substância que gere muito prazer, o cerébro fica dependente daquilo, com o tempo, a quantidade anterior, não é suficiente mais, por isso, é necessário aumentar a dosagem para receber a mesma quantidade de prazer que anteriormente recebia com uma quantidade menor">É o achatamento de atividades que são prazerosas, devido ao uso constante de alguma atividade ou substância que gere muito prazer, o cerébro fica dependente daquilo, com o tempo, a quantidade anterior, não é suficiente mais, por isso, é necessário aumentar a dosagem para receber a mesma quantidade de prazer que anteriormente recebia com uma quantidade menor<br>
                
                <input type="submit" name="EnviarRespostas" value="Enviar respostas">
                <input type="submit" name="reset" value="Refazer teste" onclick="return confirm('Tem certeza que deseja apagar todas as respostas e recomeçar?')">
            </form>
        </div>

    </main>

    <?php 
    //A FAZER
    //1)   Preciso colocar as respostas que foram marcadas e estão no banco de dados
    //como a única respostas que vão mostrar no site FEITO
    //2)   Preciso criar um botão para "resetar" o questionário e deletar as informações
    //salvas no banco de dados. FEITO
    //3)   Preciso colocar todas as perguntas corretas dentro do banco de dados FEITO
    //4)   Preciso atualizar as respostas respondidas acima com variáveis FEITO
    //5)   Preciso deixar esse código mais bonito, organizado e comentado. FEITO 
    //6)   Tem um problema que, sempre que deleto as respostas da table answers, quando atualizo a página do site, ele ainda envia as mesmas respostas salvas anteriormente, ao invés de resetar todas as respostas, que seria o correto. RESOLVIDO
    //7)   Criar um botão de alerta se o user não responder todas as perguntas.     FEITO
    //8) 
        // Run this once to populate questions
        // $questions = [
        //     [
        //         'question_text' => 'Qual o maior orgão do corpo humano ?',
        //         'correct_answer' => 'Pele'
        //     ],
        //     [
        //         'question_text' => 'Qual o único orgão do corpo humano que não aumenta de tamanho desde o nascimento ?',
        //         'correct_answer' => 'Olhos'
        //     ],
        //     [
        //         'question_text' => 'Qual o nome do hormônio responsável pelos sentimentos de recompensa e motivação, conhecido como hormônio do prazer ?',
        //         'correct_answer' => 'Dopamina'
        //     ],
        //     [
        //         'question_text' => 'Qual o nome do hormônio responsável pela resposta inflamatória do corpo, equilibrar o sistema imunológico e controlar a pressão arterial, também conhecido como hormônio do estresse ? ',
        //         'correct_answer' => 'Cortisol'
        //     ],
        //     [
        //         'question_text' => 'Como ocorre o processo de crescimento dos músculos ?',
        //         'correct_answer' => 'Primeiro ocorre pequenas lesões nas fibras musculares causadas por esforço físico e estresse mecânico que atráves de descanso  são reparadas, ficando maiores e mais fortes'
        //     ],
        //     [
        //         'question_text' => 'O que descreve melhor a sensação de vício no cerébro ?',
        //         'correct_answer' => 'É o achatamento de atividades que são prazerosas, devido ao uso constante de alguma atividade ou substância que gere muito prazer, o cerébro fica dependente daquilo, com o tempo, a quantidade anterior, não é suficiente mais, por isso, é necessário aumentar a dosagem para receber a mesma quantidade de prazer que anteriormente recebia com uma quantidade menor'
        //     ]
        // ];

        // // Insert questions only if they don't exist
        // foreach ($questions as $question) {
        //     $stmt = $connection->prepare("INSERT INTO questions (question_text, correct_answer) VALUES (?, ?)");
        //     $stmt->bind_param("ss", $question['question_text'], $question['correct_answer']);
        //     $stmt->execute();
        // }

        $correctAnswers = [
            'resposta_1' => 'Pele',
            'resposta_2' => 'Fêmur',
            'resposta_3' => 'Dopamina',
            'resposta_4' => 'Cortisol',
            'resposta_5' => 'Primeiro ocorre pequenas lesões nas fibras musculares causadas por esforço físico e estresse mecânico que atráves de descanso são reparadas, ficando maiores e mais fortes',
            'resposta_6' => 'É o achatamento de atividades que são prazerosas, devido ao uso constante de alguma atividade ou substância que gere muito prazer, o cerébro fica dependente daquilo, com o tempo, a quantidade anterior, não é suficiente mais, por isso, é necessário aumentar a dosagem para receber a mesma quantidade de prazer que anteriormente recebia com uma quantidade menor'
        ];
        
        $results = []; // Array para verificar se a resposta está correta usando 1 para true com if
        $userAnswers = [];
        // Get user ID from session
        $userId = $user['id'];
    
        if (isset($_POST["EnviarRespostas"])){

            $allAnswered = true;
            $score = 0;
            $respondido = 0;

            // First, verify all questions are answered
            foreach($correctAnswers as $answer => $correctAnswer) {
                if (!isset($_POST[$answer])) {
                    $allAnswered = false;
                    break;
                } else{
                    $respondido ++;
                }
            }

            // Se todas as perguntas forem respondidas, então procede para as query's do BD
            if ($allAnswered){
                foreach($correctAnswers as $answer => $correctAnswer){
                    $questionId = substr($answer, -1); // Gets question number (1, 2)
                    $userAnswer = null;
                    
                    //Se o user clicar em uma resposta, pega a resposta atual.
                    if (isset($_POST[$answer])) {
                        $userAnswer = $_POST[$answer];

                    } else {
                        $allAnswered = false;
                        echo '<script>alert("Por favor, responda TODAS as perguntas antes de enviar!");</script>';
                        break;
                    }
                    
                    //Se a resposta do user for a correta, recebe 1.
                    $isCorrect = ($userAnswer === $correctAnswer);
                    
                    // Seleciona as IDs e User_answers para ver se existem no BD. 
                    $sqlUserAnswersAndID = "SELECT id, user_answer FROM answers WHERE user_id = ? AND question_id = ?";
                    $stmt2 = mysqli_prepare($connection, $sqlUserAnswersAndID);
                    mysqli_stmt_bind_param($stmt2, "ii" ,$userId, $questionId);
                    mysqli_execute($stmt2);
                    $respostasUser = mysqli_stmt_get_result($stmt2);
                    
    
                    // Seleciona as IDs e Correct_answers do BD para correção.
                    $sqlCorrectAnswers = "SELECT id, correct_answer FROM questions WHERE id = ?";
                    $stmt3 = mysqli_prepare($connection, $sqlCorrectAnswers);
                    mysqli_stmt_bind_param($stmt3, "i", $questionId);
                    mysqli_execute($stmt3);
                    $respostasCorretas = mysqli_stmt_get_result($stmt3); 
    
    
                    //Se não tiver respostas no BD, inseri a resposta do usuário no BD
                    if (mysqli_num_rows($respostasUser) === 0 && $allAnswered === true) {
                        $sqlInsertAnswers = "INSERT INTO answers (user_id, question_id, user_answer, is_correct) VALUES (?, ?, ?, ?)";
                        $stmt4 = mysqli_prepare($connection, $sqlInsertAnswers);
    
                        mysqli_stmt_bind_param($stmt4, "iisi", $userId, $questionId, $userAnswer, $isCorrect);
                        mysqli_execute($stmt4);
                    }

                    // Se tiver respostas no BD, pega as respostas e compara com elas.
                    else if (mysqli_num_rows($respostasUser) > 0) {
                        $respostaUserBD = mysqli_fetch_assoc($respostasUser);
                        $respostaCorretaBD = mysqli_fetch_assoc($respostasCorretas);
    
                        if ($respostaUserBD['user_answer'] === $respostaCorretaBD['correct_answer']){
                            echo" <br>Acertou!";
                        } else{
                            echo "<br>Errou!";
                        }
                    }
    
                    if ($isCorrect) {
                        $score++;
                    }

                    // After processing all answers, store in session
                    $_SESSION['current_score'] = $score;
                    $_SESSION['current_answered'] = $respondido;
    
                    // header("Location: ".$_SERVER['PHP_SELF'] . "?submitted=true");
                    // exit();
                }

            } else {
                // Show error message if not all questions answered
                echo '<script>alert("Por favor, responda TODAS as perguntas antes de enviar!");</script>';
            }

            // Show success message
            echo "<p>Respostas enviadas com sucesso! Você acertou $score de " . count($correctAnswers) . " perguntas.</p>";

            // Force redirect to refresh the page
            echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'";</script>';
            exit();
        }

        // Deleta as respostas que ja estão salvas no BD
        if(isset($_POST["reset"])){

            $sqlDelete = "DELETE FROM answers WHERE user_id = ? ";
            $stmtDelete = mysqli_prepare($connection, $sqlDelete);

            mysqli_stmt_bind_param($stmtDelete, "i" ,$userId);
            mysqli_execute($stmtDelete);

            if (mysqli_execute($stmtDelete)) {
                echo '<script>alert("Respostas apagadas com sucesso! Você pode recomeçar o teste.");</script>';
                // Optional: Redirect to clear form
                echo '<script>window.location.href = "'.$_SERVER['PHP_SELF'].'";</script>';
            } else {
                echo '<script>alert("Erro ao apagar respostas: '.$connection->error.'");</script>';
            }

            $_SESSION['current_score'] = 0;
            $_SESSION['current_answered'] = 0;

            // // Force redirect to refresh the page
            echo '<script>window.location.href="'.$_SERVER['PHP_SELF'].'";</script>';
            exit();
        }
    ?>
</body>
</html>


