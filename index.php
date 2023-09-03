<?php
session_start();
if (!isset($_SESSION['uid'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}

define('DESC', 'Screencast TV');
$html = '';

require_once './config.php';
require_once './core.php';

$pdo = connectDB($db);


  $sql="SELECT * FROM Displays";
                   
  $stmt = $pdo->prepare($sql);
 
  
  // Executar query
  if ($stmt->execute()) {
      $reg = $stmt->fetch();
      $DEBUG .= "Obter dados da BD: " . print_r($reg, true) . "\n";
  } else {
      $result .= '<div class="alert alert-danger">Ocorreu um erro ao ler registo da Base de Dados.</div>';
  }
  
// Obter módulo e ação a carregar
$module = filter_input(INPUT_GET, 'm', FILTER_SANITIZE_STRING);
$action = filter_input(INPUT_GET, 'a', FILTER_SANITIZE_STRING);

// Verificar módulo
$module = ($module == '') ? 'home' : $module;

$list = $pdo->query($sql)->fetchAll();
$num = count($list);                                                                                                                                                 
?>
<!doctype html>
<html lang="en">
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
   
    <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,500i,600,600i,700,700i|Playfair+Display:400,400i,500,500i,600,600i,700,700i,900,900i" rel="stylesheet">

<!-- Vendor CSS Files -->

  </head>
  <body>
		
		<div class="wrapper d-flex align-items-stretch">
			<nav id="sidebar">
				<div class="p-4 pt-5">
		  		<a href="index.php" class="img logo rounded-circle mb-5" style="background-image: url(img/logo2.png);"></a>
	        <ul class="list-unstyled components mb-5">
	          <li class="active">
	            <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><?= $_SESSION['username'] ?> &emsp;&emsp;&emsp; <img class="circular--square" src="<?= $_SESSION['avatar'] ?>" width="30" alt="avatar"></a>
	            <ul class="collapse list-unstyled" id="homeSubmenu">
             
                <li>
                    <a href="?m=changes&a=update ">Dados Pessoais</a>
                </li>
	            </ul>
	          </li>
            <li>
                    <?= is_admin() ? '<a  href="?m=users&a=read">Users</a>' : '' ?>
            </li>
	          <li>
	             
                <?= is_admin() ? ' <a href="?m=displays&a=read">Displays</a>' : '' ?>
	          </li>
            <li>
              
              <?= is_admin() ? '<a href="?m=conteudo&a=read">Conteúdo</a>' : '' ?>
	          </li>
	          <li>
              
              <?= is_admin() ? '<a href="?m=playlist&a=read">Playlist</a>' : '' ?>
	          </li>
	          <li>
              
              <?= is_admin() ? '<a href="?m=categorias&a=read">Categorias</a>' : '' ?>
	          </li>
            <li>
              
              <?= is_admin() ? '<a href="?m=downloads&a=read">Downloads</a>' : '' ?>
	          </li><br><br>
            <li class="active">
              <a href="logout.php">Terminar Sessão <i class="fa-solid fa-right-from-bracket"></i></a>
	          </li>
	        </ul>
     
	      </div>
    	</nav>

        <!-- Page Content  -->
      <div id="content" class="p-4 p-md-5">

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <div class="container-fluid">

            <button type="button" id="sidebarCollapse" class="btn btn-primary">
              <i class="fa fa-bars"></i>
              <span class="sr-only">Toggle Menu</span>
            </button>
            <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="nav navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?m=contact&a=read">Contact</a>
                </li>
              </ul>
            </div>
          </div>
        </nav>

        <!--<h2 class="mb-4">Screencast TV</h2>-->
        <div class="row">
                <div class="col-md-12">
                    <!--<div class="jumbotron">--> 
                    <?php
                    if ($module=='home'){
                        ?>
                        <h2><?= DESC ?></h2>
                        Utilize uma das opções disponíveis.

              





<br>
                        <!-- Carousel wrapper -->
                        
<div
  id="carouselMultiItemExample"
  class="carousel slide carousel-dark text-center"
  data-mdb-ride="carousel"
>


  <!-- Inner -->
  <div class="carousel-inner py-4">
    <!-- Single item -->
    <div class="carousel-item active">
      <div class="container">
        <div class="row">
        <?php
    if ($num > 0) { 
        foreach ($list as $reg) {
            ?>
          <div class="col-lg-4" >
            <div class="card">
              <img
                src="<?= $reg['image'] ?>"
                class="card-img-top"
                alt="..."width="200" height="200"
              />
              <div class="card-body">
                <h5 class="card-title"><?= $reg['descricao'] ?></h5>
                
                <p class="card-text">
               <!--<?= $reg['token'] ?>-->
                </p>
                <div class="ml-sm-auto">
                  <a href="https://alpha.soaresbasto.pt/~andredias/ScreencastTV/playslide.php?token=<?= $reg['token'] ?>" target="_blank" class="btn btn-primary" title="Novo"><i class="fi fi-plus-a"></i> Ver</a>
                </div>
              </div>
            </div>
          </div>


          <?php
        }
    }else {
        ?>
            <tr>
                <td colspan="6">Sem registos</td>
            </tr>
        <?php 
    }
    ?>
       

          
        </div>
      </div>
    </div>      
        </div>
      </div>
    </div>
  </div>
  <!-- Inner -->
</div>
<!-- Carousel wrapper -->


                        <?php
                    }else{
                        require_once "./mod/$module/$action.php";
                    }
                    ?>
                    <!--</div>-->
                </div>
            </div>
          <!--
            <div class="row">
                <div class="col-md-12">
                <?= debug() ? '<div class="jumbotron"><pre>'.$DEBUG.'<br>POST: ' . print_r($_POST, true) . '<br>GET: ' . print_r($_GET, true) . '<br>SESSION: ' . print_r($_SESSION, true) . '</pre></div>' : '' ?>
                </div>
            </div>
                  -->
        </div>
      </div>
		</div>

    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>

  </body>
</html>