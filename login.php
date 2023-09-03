<?php
session_start();
define('DESC', 'Fazer login de um utilizador');
$html = '';

require_once './config.php';
require_once './core.php';


$login = filter_input(INPUT_POST, 'login');
if ($login) {
    $pdo = connectDB($db);


    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $password_hash_db = password_hash($password, PASSWORD_DEFAULT);

    $errors = false;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $html .= ' <br> <div class="container alert-danger">O email não é válido.</div>';
        $errors = true;
    }
    
    if (!$errors) {
        $sql = "SELECT * FROM `users` WHERE `email` = :EMAIL LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":EMAIL", $email, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() != 1) {
            $html .= ' <br> <div class="container alert-danger">O email indicado não se encontra registado.</div>';
            $errors = true;
        } else {
            $row = $stmt->fetch();
        }
    }
    
    if (!$errors) {
        if (!password_verify($password, $row['password'])) {
            
            $html .= '<br> <div class="container alert-danger">Palavra-passe incorreta.</div>';
            sleep(random_int(1, 3));
        } else {
            $_SESSION['uid'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['avatar'] = $row['avatar'] != '' ? $row['avatar'] : 'avatar.png';
            $_SESSION['profile'] = $row['profile'];
            $html .= '<div class="container alert-success">Login com sucesso! <br> <b>' . $_SESSION['username'] . '</b></div>';
            $html .= '<div class="container alert-success"><a href="index.php" class="btn btn-primary">Continuar</a></div>';
            header("location: index.php");
            exit();
        }
    }
}
?>

    <!DOCTYPE html>
<html>

<head>
<title>ScreencastTV</title>
    <meta charset="utf-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="img/minilogo2.png">
  	<link rel="icon" type="image/png" sizes="96x96" href="img/minilogo2.png">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/estilo.css" rel="stylesheet">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="css/style.css">
    <link href="css/fontisto-3.0.4/fontisto.css" rel="stylesheet" type="text/css">
    <link href='css/conteudo.scss' rel='stylesheet' type='text/css'>
    <link href='js/conteudo.js' rel='stylesheet' type='text/css'>
    <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,500i,600,600i,700,700i|Playfair+Display:400,400i,500,500i,600,600i,700,700i,900,900i" rel="stylesheet">

</head>


    <body>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>


    <div class="sidenav" >
        <div class="login-main-text">

        <br><br>
        <a href="./index.html">
            <img src="img/wordlogo2.png" height="50" alt="yes"> 
        </a>
            <p>  &emsp;Faz login para acederes às tuas playlists favoritas.</p>
        </div>
    </div>

<div class="main">

    <div class="col-md-6 col-sm-12">
        <div class="login-form">
        <div class="container">
    
                <form action="?" method="POST">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Endereço de Email">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Palavra-Passe">
                    </div>
                    <input type="submit" class="btn btn-black" name="login" value="Login">
                </form>
                <div class="container"><?= $html ?></div>
        </div>
               
           
        </div>

    </div>
</div>
        <style>

    

    body {
        font-family: "Lato", sans-serif;
    }

    .main-head{
        height: 150px;
        background: #FFF;
        
    }
    
    .sidenav {
        height: 100%;
        background: #91C0F4;
        background: -webkit-linear-gradient(-135deg, #0F0F0F, #0F0F0F);
        background: -o-linear-gradient(-135deg, #0F0F0F, #0F0F0F);
        background: -moz-linear-gradient(-135deg, #0F0F0F,#000000);
        background: linear-gradient(-135deg, #0F0F0F, #0F0F0F);
        background-image: fill;
        overflow-x: hidden;
        padding-top: 20px;
      
    }
    
    .main {
        padding: 0px 10px;
    }
    
    @media screen and (max-height: 450px) {
        .sidenav {padding-top: 15px;}
    }
    
    @media screen and (max-width: 450px) {
        .login-form{
            margin-top: 10%;
        }

    }
    
    @media screen and (min-width: 768px){
        .main{
            margin-left: 40%; 
        }
    
        .sidenav{
            width: 40%;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
        }
    
        .login-form{
            margin-top: 80%;
        }

    }
    
    .login-main-text{
        margin-top: 20%;
        padding: 60px;
        color: #fff;
 
        
    }
    
    .login-main-text h2{
        font-weight: 300;
        color: #fff;
        text-decoration: none;
    }
    
    .btn-black{
        background-color: #F1B737 !important;
        color: #fff;
    }
</style>
    </body>
</html>