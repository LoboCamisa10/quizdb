<?php

    $dbServer = "localhost";
    $dbUser = "root";
    $dbPassword = "";
    $dbName = "quizdb";
    $connection = "";

    try{
        $connection = mysqli_connect($dbServer, $dbUser, $dbPassword, $dbName);
    }

    catch(mysqli_sql_exception){
        echo "Não conseguiu conectar no BD ! <br>";
    }

    // if($connection){
    //     echo"Você está conectado ao BD ! <br>";
    // }

?>