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
$TITLE = 'Displays';

$DEBUG .= "Ligar à BD\n";
$pdo = connectDB($db);

// Criar query
$sql ="SELECT * FROM Displays";
$DEBUG .= "Query SQL: $sql\n";

// Executar query e obter dados da Base de Dados
$list = $pdo->query($sql)->fetchAll();

// Contar número de registos
$num = count($list);
$DEBUG .= "Número de registos: $num\n";


?>

  <head>
  	<title></title>
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

<!-- Vendor CSS Files -->

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





<!--
<div class="table-responsive-md">
<table class="table table-striped table-hover table-sm">
    <thead>
        <tr>
            <th class="align-middle">ID</th>
            <th class="align-middle">Descrição</th>
            <th class="align-middle">MacAddress</th>
            <th class="align-middle">Token</th>
            <th class="align-middle">Data</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($num > 0) {
        foreach ($list as $reg) {
            ?>
            <tr>
        -->



        <div class="container">
    <div class="col-md-12">
        <div class="card b-1 hover-shadow mb-20">
            <div class="media card-body">
                <div class="media-left pr-12">
                    <img class="avatar avatar-xl no-radius" src="<?= $reg['image'] ?> " alt="..." >  <!--Adicionar Fotos-->
                </div>
                <div class="media-body">
                    <div class="mb-2">
                        <span class="fs-20 pr-16">&ensp;<?= $reg['descricao'] ?></span>
                    </div>
                    <!--<small class="fs-16 fw-300 ls-1">&ensp;<?= $reg['descricao'] ?></small>-->
                </div>
                <div class="media-right text-right d-none d-md-block">
                    <p class="fs-14 text-fade mb-12"><i class="#">Mac Address : </i><?= $reg['macaddress'] ?></p>
                    <span class="text-fade"><i class="#">Token : </i><?= $reg['token'] ?></span>
                </div>
            </div>
            <footer class="card-footer flexbox align-items-center">
                <div>
                    <strong>Criado a:</strong>
                    <span><?= $reg['data_criacao'] ?></span>
                </div>
    
                <div class="card-hover-show">

                    
                    <a href="?m=<?= $module ?>&a=add&id=<?= $reg['ID'] ?>" class="btn btn-outline-warning" title="Ver Detalhes"> 
                    <i class="bi bi-collection-play"></i> 
                    </a>

                    <a href="?m=<?= $module ?>&a=update&id=<?= $reg['ID'] ?>" title="Editar" class="btn btn-outline-dark">
                    <i class="bi-pencil-square"></i>  Editar
                    </a>
              

                         <a href="?m=<?= $module ?>&a=delete&id=<?= $reg['ID'] ?>" title="Eliminar" class="btn btn-outline-danger"
                       onclick="return confirm('Pretende eliminar o registo <?= $reg['ID'] ?> - <?= $reg['descricao'] ?>?');">
                        <i class="fi fi-trash"></i>
                        
                    </a>
                </div>
            </footer>
        </div>
        <br>
    </div>
</div>





        <!--
                <td class="align-middle"><?= $reg['ID'] ?></td>
                <td class="align-middle"><?= $reg['descricao'] ?></td>
                <td class="align-middle"><?= $reg['macaddress'] ?></td>
                <td class="align-middle"><?= $reg['token'] ?></td>
                <td class="align-middle"><?= $reg['data_criacao'] ?></td>
                <td class="align-middle">

                <a href="?m=<?= $module ?>&a=add&id=<?= $reg['ID'] ?>" class="btn btn-outline-warning" title="Ver Detalhes"> 
                        <i class="fi fi-eye"></i> 
                    </a>

                    <a href="?m=<?= $module ?>&a=update&id=<?= $reg['ID'] ?>" title="Editar" class="btn btn-secondary">
                        <i class="fi fi-prescription"></i>  Editar
                    </a>
              

                         <a href="?m=<?= $module ?>&a=delete&id=<?= $reg['ID'] ?>" title="Eliminar" class="btn btn-danger"
                       onclick="return confirm('Pretende eliminar o registo <?= $reg['ID'] ?> - <?= $reg['descricao'] ?>?');">
                        <i class="fi fi-trash"></i>
                    </a>

  
      
                </td>
            </tr>
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
    </tbody>
</table>
</div>  -->

<?php
    }
?>