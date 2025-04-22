<?php
    session_start();

    if (isset($_FILES["profile-photo"]) && $_FILES["profile-photo"]["error"] == 0) {
        $targetDir = "images/";
        $fileName = basename($_FILES["profile-photo"]["name"]);
        $targetFile = $targetDir . time() . "_" . $fileName; // Adiciona registro de data e hora ao nome do arquivo para evitar duplicatas

        // Verifica se é uma imagem
        $check = getimagesize($_FILES["profile-photo"]["tmp_name"]);
        if ($check !== false) {
            //Move do local de upload temporário para o armazenamento permanente
            if (move_uploaded_file($_FILES["profile-photo"]["tmp_name"], $targetFile)) {
                // Salva caminho na sessão
                $_SESSION["photo"] = $targetFile;
                header("Location: question.php"); // ou o nome da página principal
                exit();
            } else {
                echo "Erro ao salvar o arquivo.";
            }
        } else {
            echo "O arquivo não é uma imagem válida.";
        }
    } else {
        echo "Nenhuma imagem enviada ou erro no envio.";
    }
    ?>
    
</body>
</html>