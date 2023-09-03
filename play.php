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

        <meta name="description" content="<?= DESC ?>">
        <meta name="author" content="<?= AUTHOR ?>">

        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link href="css/fontisto-3.0.4/fontisto.css" rel="stylesheet" type="text/css">
        <script src="https://kit.fontawesome.com/41bcea2ae3.js"> </script>
</head>
<body id="body">

<p>Token é este <?= $token ?></p>
<p>Display é este <?= $list[0]['descricao'] ?> id: <?= $list[0]['ID'] ?></p>

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

foreach($playlist as $p) {

?>
<p><?= $p['Playlist_ID'] ?></p>

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
            <?= $conteudo['Conteudo_ID']?>
            <img src="<?= $conteudo['url'] ?>">
        <?php
        } else if ($conteudo['categorias_idTipos'] == 8){
        ?>
            <?= $conteudo['Conteudo_ID']?>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/<?= $conteudo['url'] ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        <?php
        }
        
    }   

}

?>


<?php
    
        foreach ($conteudos as $conteudo) {
            ?>
<div id="CrossFade">
<img src="<?= $conteudo['url'] ?>">
  <div class="intro">
    <h1>Lorem ipsum dolor sit amet</h1>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perferendis impedit facilis nesciunt quam vitae voluptatibus ullam vero.</p>
  </div>
</div>
<?php
        }
        ?>
    



<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/scripts.js"></script>

</body>
</html>