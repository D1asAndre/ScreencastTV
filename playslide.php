<?php
require_once './config.php';
require_once './core.php';

$DEBUG .= "Ligar à BD\n";
$pdo = connectDB($db);  
// Obter token pelo URL de forma segura
$token = filter_input(INPUT_GET, 'token');

//Criar query
$sql = "SELECT * FROM Displays WHERE token = '$token'";

$list = $pdo->query($sql)->fetchAll();

$num = count($list);    
$DEBUG .= "Número de registos: $num\n";

$stmt = $pdo->prepare($sql);
// Associar valor do ID
$stmt->bindValue(':token', $token, PDO::PARAM_INT);

// Executar query
if ($stmt->execute()) {
    $reg = $stmt->fetch();
    $DEBUG .= "Obter dados da BD: " . print_r($reg, true) . "\n";
} else {
    $result .= '<div class="alert alert-danger">Ocorreu um erro ao ler registo da Base de Dados.</div>';
}

?>
<!DOCTYPE html>

<head>
<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title></title>

        <meta name="description" >
        <meta name="author" content="<?= AUTHOR ?>">

        <link href="css/bootstrap.min.css" rel="stylesheet">
      <!-- Favicons -->

    <link rel="apple-touch-icon" sizes="76x76" href="img/minilogo2.png">
      <link rel="icon" type="image/png" sizes="96x96" href="img/minilogo2.png"> 
        <link href="css/fontisto-3.0.4/fontisto.css" rel="stylesheet" type="text/css">
        <script src="https://kit.fontawesome.com/41bcea2ae3.js"> </script>
        
        
</head>
<body id="body">
<header class="intro-header" align="center">
   <!--Intro Section-->
   <section class="view intro-video">
    
    <div class="mask rgba-gradient">
        <div class="full-bg-img">
            <div class="container flex-center">
                <div class="row pt-5 mt-3">
                    <div class="col-lg-4 wow fadeIn mb-5 text-center text-lg-left">
                        <div class="white-text">
                            <h2 class="h1 h1-responsive font-bold wow fadeInLeft" data-wow-delay="0.3s">Apresentação de Conteúdo</h2>
                            <hr class="hr-light wow fadeInLeft" data-wow-delay="0.3s">
                            <p class="wow fadeInLeft" data-wow-delay="0.3s">Aqui ficam disponíveis os conteúdos pertencentes a cada televisão.</p>
                            <br>

                        </div>
                    </div>

                    <div class="col-lg-8 wow fadeIn">
                        <div class="embed-responsive-item embed-responsive-16by9 wow fadeInRight">
                        

<!--
<p>Token é este <?= $token ?></p>
<p>Display é este <?= $list[0]['descricao'] ?> id: <?= $list[0]['ID'] ?></p>
-->
<?php
$sql  = 'SELECT * FROM `Playlist_has_Displays` WHERE Displays_ID = :Display_ID';
$stmt = $pdo->prepare($sql);
// Associar valor do ID
$stmt->bindValue(':Display_ID', $list[0]['ID'], PDO::PARAM_INT);

// Executar query
if ($stmt->execute()) {
    $playlist = $stmt->fetchAll();
    $DEBUG .= "Obter dados da BD: " . print_r($reg, true) . "\n";
} else {
    $result .= '<div class="alert alert-danger">Ocorreu um erro ao ler registo da Base de Dados.</div>';
}

?>
    <div id="slideshow" class="carousel slide" data-ride="carousel" data-pause="false" data-intervale="1000">
    <div class="carousel-inner">
   <?php
  $active = true; 
foreach($playlist as $p) {

?>
<!--<p><?= $p['Playlist_ID'] ?></p>-->

    <?php
    $sql  = 'SELECT * FROM `Playlist_has_Conteudo`, `Conteudo` WHERE Conteudo_ID = Conteudo.ID and Playlist_ID = :Playlist_ID';
    $stmt = $pdo->prepare($sql);
    // Associar valor do ID
    $stmt->bindValue(':Playlist_ID', $p['Playlist_ID'], PDO::PARAM_INT);

    // Executar query
    if ($stmt->execute()) {
        $conteudos = $stmt->fetchAll();
        $DEBUG .= "Obter dados da BD: " . print_r($reg, true) . "\n";
    } else {
        $result .= '<div class="alert alert-danger">Ocorreu um erro ao ler registo da Base de Dados.</div>';
    }

   

    foreach($conteudos as $conteudo) {
        if ($conteudo['categorias_idTipos'] == 1  )  {
        ?>
        
            <div class="carousel-item <?= $active?'active':''?>" >
                <img class="d-block w-100" src="<?= $conteudo['url'] ?>" alt="<?= $conteudo['Conteudo_ID']?>" > 
                <div class="carousel-caption d-none d-md-block">
                    <h5 style="color: white; font-size: 30px;
  text-shadow: 2px 2px 4px #000000;"><?= $conteudo['titulo'] ?></h5>
                    <p style="color: white; font-size: 15px;
  text-shadow: 2px 2px 4px #000000;"><?= $conteudo['descricao'] ?></p>
                </div>
                    
            </div>
            
        <?php
        } else if ($conteudo['categorias_idTipos'] == 8){
        ?>
           <!-- <?= $conteudo['Conteudo_ID']?>-->
           <div class="carousel-item <?= $active?'active':''?>" >
            <iframe width="100%" height="50%" src="https://www.youtube.com/embed/<?= $conteudo['url'] ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>     
        <?php
        }
    $active = false;
    }   

}
?>
</div>
<a class="carousel-control-prev" href="#slideshow" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
</a>
<a class="carousel-control-next" href="#slideshow" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
</a>
</div>
<br>
<div class="ml-sm-auto">
        <a onclick="openFullscreen();"  class="btn btn-primary" title="Novo">Fullscreen Mode </a>
</div>

<script>
    var elem = document.getElementById("slideshow");
    function openFullscreen() {
    if (elem.requestFullscreen) {
        elem.requestFullscreen();
    } else if (elem.webkitRequestFullscreen) { /* Safari */
        elem.webkitRequestFullscreen();
    } else if (elem.msRequestFullscreen) { /* IE11 */
        elem.msRequestFullscreen();
    }
    }
</script>
  
    
<style>

    .carousel-caption {
    position: absolute;
    right: 15%;
    top: 20px;
    left: 15%;
    z-index: 10;
    padding-top: 20px;
    padding-bottom: 20px;
    color: #fff;
    text-align: center; }
</style>


<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/scripts.js"></script>

</body>
</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</header>
</html>