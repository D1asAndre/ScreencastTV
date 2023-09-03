<?php
if (count(get_included_files()) == 1) {
    header('Location: ../../index.php');
    exit("Direct access not permitted.");
}

if ($_SESSION['profile'] != "admin"){

  echo'
  <div class="container-fluid">
  
      <div class="text-center">
          <div class="error mx-auto" data-text="404">404</div>
          <p class="lead text-gray-800 mb-5">Page Not Found</p>
          <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
          <a href="index.php">&larr; Volta para a Página Principal</a>
      </div>
  
  </div>
  
  </div>
  ';
  
  
  } else {
$TITLE = 'Conteúdo';

$DEBUG .= "Ligar à BD\n";
$pdo = connectDB($db);

// Criar query
$sql ="SELECT Conteudo.ID as conteudoID, Conteudo.titulo, Conteudo.descricao, Conteudo.url, Conteudo.categorias_idTipos, Conteudo.datacriacao, categorias.ID, categorias.tipo FROM Conteudo, categorias WHERE Conteudo.categorias_idTipos = categorias.ID";
$DEBUG .= "Query SQL: $sql\n";

// Executar query e obter dados da Base de Dados
$list = $pdo->query($sql)->fetchAll();

// Contar número de registos
$num = count($list);    
$DEBUG .= "Número de registos: $num\n";


?>
<html lang="en">
  <head>
  	<title>Sidebar 01</title>
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
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,500i,600,600i,700,700i|Playfair+Display:400,400i,500,500i,600,600i,700,700i,900,900i" rel="stylesheet">


  </head>


<div class="row">
    <div class="col-sm-auto">
        <h3><?= $TITLE ?></h3>
    </div>
    <div class="ml-sm-auto">
        <a href="?m=<?= $module ?>&a=create" class="btn btn-primary" title="Novo"><i class="fi fi-plus-a"></i> Novo</a>
    </div>
    
</div>
<br>
<br>
<br>




    <div class="container-fluid"><!--1-->
    <div class="px-lg-5"><!--2-->


      <div class="row"><!--3-->
    <?php
    if ($num > 0) { 
        foreach ($list as $reg) {
            ?>






        <!-- Gallery item -->
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4"><!--4-->
          <div  id="myImg" class="bg-white rounded shadow-sm"><a href="#"> <img src="<?= $reg['url'] ?>" alt="" class="img-fluid card-img-top"><!--5-->
         


            <div class="p-4"><!--6-->
              <h5> <a href="#" class="text-dark"><?= $reg['titulo'] ?></a></h5>
              <p class="small text-muted mb-0"><b>Criado a:</b> <br><?= $reg['datacriacao'] ?></p>
              <div class="d-flex align-items-center justify-content-between rounded-pill bg-light px-3 py-2 mt-4">
                <p class="small mb-1">
            
                <!--<span class="font-weight-bold"><?= $reg['tipo'] ?></span>-->
            
            
                <a href="?m=<?= $module ?>&a=update&id=<?= $reg['conteudoID'] ?>" title="Editar" class="btn btn-outline-warning">
                  <i class="bi-pencil-square"></i>
                </a>
                <a href="?m=<?= $module ?>&a=delete&id=<?= $reg['conteudoID'] ?>" title="Eliminar"  class="btn btn-outline-danger"
                  onclick="return confirm('Pretende eliminar o registo <?= $reg['conteudoID'] ?> - <?= $reg['titulo'] ?>?');">
                  <i class="fi fi-trash"></i>      
                </a>

                </p>
              </div><!--6-->
            </div><!--5-->
          </div><!--4-->
        </div><!--3-->
        <!-- End -->
  
  
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
 
</div><!--2-->  

</div><!--1-->

<?php
    }
?>
