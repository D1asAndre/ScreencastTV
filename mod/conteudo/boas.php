<?php
if (count(get_included_files()) == 1) {
    header('Location: ../../index.php');
    exit("Direct access not permitted.");
}

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
    
    <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,500i,600,600i,700,700i|Playfair+Display:400,400i,500,500i,600,600i,700,700i,900,900i" rel="stylesheet">

<!-- Vendor CSS Files -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">

    
    <link rel="stylesheet" href="css/style.css" >
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
<hr>
<div class="col-sm-auto" align="right">
            <form action="?m=conteudo&a=read" role="form" method="post">
                <input type="text" id="searchField" name="searchField" placeholder="Procurar">

                <select id="searchTypeField" name="searchTypeField">
                    <option value="conteudo.ID">ID</option>
                    <option value="titulo">Título</option>
                    <option value="descricao">Descricão</option>
                    <option value="url">Url</option>
                    <option value="tipo">Categoria</option>
                </select>
            </form>
    </div>

<br>
<br>





<!--
<div class="table-responsive-md">
<table class="table table-striped table-hover table-sm">
    <thead>
        <tr>
            <th class="align-middle">ID</th>
            <th class="align-middle">Título</th>
            <th class="align-middle">Descrição</th>
            <th class="align-middle">URL</th>
            <th class="align-middle">Tipo</th>
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
                    <img class="avatar avatar-xl no-radius" src="<?= $reg['url'] ?> " alt="..." >
                </div>
                <div class="media-body">
                    <div class="mb-2">
                        <span class="fs-20 pr-16"><?= $reg['titulo'] ?></span>
                    </div>
                    <small class="fs-16 fw-300 ls-1"><?= $reg['descricao'] ?></small>
                </div>
                <div class="media-right text-right d-none d-md-block">
                    <p class="fs-14 text-fade mb-12"><i class="#"></i><?= $reg['tipo'] ?></p>
                    <span class="text-fade"><i class="#"></i><?= $reg['tipo'] ?></span>
                </div>
            </div>
            <footer class="card-footer flexbox align-items-center">
                <div>
                    <strong>Criado a:</strong>
                    <span><?= $reg['datacriacao'] ?></span>
                </div>
    
                <div class="card-hover-show">

                    <?php
                        if ($reg['categorias_idTipos'] == 1  )  {
                    ?>
                            <a href="<?=$reg['url']?>" target="_blank" class="btn btn-secondary">
                            <i class="fi fi-eye"></i></i>
                            </a>
                    <?php
                        } else if ($reg['categorias_idTipos'] == 8){
                    ?>
                            <a href =  "https://www.youtube.com/watch?v=<?= $reg['url'] ?>" target="_blank" class="btn btn-secondary" >
                            <i class="fi fi-eye"></i>
                            </a>
                    
                    <?php
                    }
                    ?>
                    <a href="?m=<?= $module ?>&a=update&id=<?= $reg['conteudoID'] ?>" title="Editar" class="btn btn-secondary">
                        <i class="fi fi-prescription"></i>  Editar
                    </a>
                    <a href="?m=<?= $module ?>&a=delete&id=<?= $reg['conteudoID'] ?>" title="Eliminar" class="btn btn-danger"
                       onclick="return confirm('Pretende eliminar o registo <?= $reg['ID'] ?> - <?= $reg['titulo'] ?>?');">
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
                <td class="align-middle"><?= $reg['titulo'] ?></td>
                <td class="align-middle"><?= $reg['descricao'] ?></td>
                <td class="align-middle"><?= $reg['url'] ?> 
                
                <?php
                if ($reg['categorias_idTipos'] == 1  )  {
                ?>
                <a href="<?=$reg['url']?>" target="_blank" class="btn btn-secondary">
                <i class="fi fi-eye"></i></i>
                </a>
                <?php
                } else if ($reg['categorias_idTipos'] == 8){
                ?>
                    <a href =  "https://www.youtube.com/watch?v=<?= $reg['url'] ?>" target="_blank" class="btn btn-secondary" >
                    <i class="fi fi-eye"></i>
                </a>
                
                <?php
                }
                ?>

                </td>
                <td class="align-middle"><?= $reg['tipo'] ?></td>
                <td class="align-middle"><?= $reg['datacriacao'] ?></td>
                <td class="align-middle">
                    <a href="?m=<?= $module ?>&a=update&id=<?= $reg['conteudoID'] ?>" title="Editar" class="btn btn-secondary">
                        <i class="fi fi-prescription"></i>  Editar
                    </a>
                    
                    <a href="?m=<?= $module ?>&a=delete&id=<?= $reg['conteudoID'] ?>" title="Eliminar" class="btn btn-danger"
                       onclick="return confirm('Pretende eliminar o registo <?= $reg['ID'] ?> - <?= $reg['titulo'] ?>?');">
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
</div>-->
