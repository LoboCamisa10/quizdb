<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>VOCÊ ESTÁ ON !</h1>
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <input type="submit" name="logout" value="logout">
    </form>
</body>
</html>

<?php 
    if(isset($_POST["logout"])){
        session_destroy();
        header("Location: index.php");
    }
?>