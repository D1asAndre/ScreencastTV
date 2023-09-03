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
$DEBUG .= "Ligar à BD\n";
$pdo = connectDB($db);

// Obter ID pelo URL de forma segura
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$DEBUG .= "Obter dados do URL: id=$id\n";
$TITLE = 'Adicione Fotos e Vídeos na Playlist '.$id;

// Verificar se foi feito submit ao formulário
$update = filter_input(INPUT_POST, 'update', FILTER_SANITIZE_STRING);

// Adicionar Playlist a Displays                                                            
if ($update == 'addProduto') {                          
    $result = '';
    // Obter valores do Formulário de forma segura
    $DEBUG .= "Obter dados do Formulário:\n";
    $Playlist_ID = filter_input(INPUT_POST, 'Playlist_ID', FILTER_SANITIZE_STRING);

    
    // Criar query para inserir na BD                                           ----------
    $sql = "INSERT INTO
                    Playlist_has_Displays(Playlist_ID,Displays_ID)
                    VALUES(:Playlist_ID, :Displays_ID)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":Playlist_ID", $Playlist_ID, PDO::PARAM_INT);
    $stmt->bindValue(":Displays_ID", $id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        $result .= '<div class="alert alert-success">Produto adicionado com sucesso!</div>';
    } else {
        $result .= '<div class="alert alert-danger">Erro ao adicionar produto.</div>';
    }
}
// Remover Playlist de Displays                                                 ----------
elseif ($update == 'delProduto') {
    $result = '';
    // Obter valores do Formulário de forma segura
    $DEBUG .= "Obter dados do Formulário:\n";
    $Playlist_ID = filter_input(INPUT_POST, 'Playlist_ID', FILTER_SANITIZE_NUMBER_INT);
    
    // Criar query para inserir na BD
    $sql = "DELETE FROM
                    Playlist_has_Displays
                    WHERE
                        Playlist_ID = :Playlist_ID
                        AND
                        Displays_ID = :Displays_ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":Playlist_ID", $Playlist_ID, PDO::PARAM_INT);
    $stmt->bindValue(":Displays_ID", $id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        $result .= '<div class="alert alert-success">Produto removido com sucesso!</div>';
    } else {
        $result .= '<div class="alert alert-danger">Erro ao remover produto.</div>';
    }
}



// Criar query para apresentação dos dados da Displays
$sql = "SELECT * FROM Displays WHERE ID = :ID LIMIT 1";
// Preparar Query
$stmt = $pdo->prepare($sql);
// Associar valor do ID
$stmt->bindValue(':ID', $id, PDO::PARAM_INT);
// Executar query
if ($stmt->execute()) {
    $enc = $stmt->fetch();
    $DEBUG .= "Obter dados da BD: " . print_r($enc, true) . "\n";
} else {
    $result .= '<div class="alert alert-danger">Ocorreu um erro ao ler registo da Base de Dados.</div>';
}

// Obter dados para listar Playlists existentes na Displays                        
$sql ="SELECT
PL.ID, PL.nome, PL.descricao, PL.data_criacao
FROM
    Playlist AS PL,Playlist_has_Displays AS PD
WHERE
    PL.ID = PD.Playlist_ID
    AND
    PD.Displays_ID = :ID";
$DEBUG .= "Query SQL: $sql\n";
$stmt = $pdo->prepare($sql);
// Associar valor do ID
$stmt->bindValue(':ID', $id, PDO::PARAM_INT);
// Executar query e obter dados da Base de Dados
if ($stmt->execute()) {
    $encProdutos = $stmt->fetchAll();
    $DEBUG .= "Obter dados da BD: " . print_r($encProdutos, true) . "\n";
} else {
    $encProdutos = array();
}
// Contar número de registos
$DEBUG .= "Número de produtos na encomenda: ".count($encProdutos)."\n";


// Obter dados para listar Playlists que nao estao na Displays
$sql ="SELECT
            PL.ID, PL.nome, PL.descricao, PL.data_criacao
            FROM
                Playlist AS PL
            WHERE
                PL.ID NOT IN
                (SELECT Playlist_ID FROM Playlist_has_Displays AS PD WHERE PD.Displays_ID = :ID)";
$DEBUG .= "Query SQL: $sql\n";
$stmt = $pdo->prepare($sql);
// Associar valor do ID
$stmt->bindValue(':ID', $id, PDO::PARAM_INT);
// Executar query e obter dados da Base de Dados
if ($stmt->execute()) {
    $novosProdutos = $stmt->fetchAll();
    $DEBUG .= "Obter dados da BD: " . print_r($novosProdutos, true) . "\n";
} else {
    $novosProdutos = array();
}
// Contar número de registos
$DEBUG .= "Número de produtos para adicionar: ".count($novosProdutos)."\n";
?>
<div class="row">
    <div class="col-sm-auto"><h3><?= $TITLE ?></h3></div>
    <div class="ml-sm-auto">
        <a href="?m=<?= $module ?>&a=read" class="btn" title="Fechar"><i class="fi fi-close"></i></a>
    </div>
</div>
<!-- 
<div class="row">
    <div class="col-sm-auto">Data: <?= $enc['datacriacao'] ?></div>
</div>
-->
<div class="row">
    <div class="col-12">
    <?= isset($result) ? $result : '' ?>
    </div>
</div>

<hr>
<h5>Playlists em Displays</h5>
<br>
<!--
<?php
if (count($encProdutos) > 0) {
    foreach ($encProdutos as $reg) {
        ?>
        <form action="?m=<?= $module ?>&a=add&id=<?= $id ?>" role="form" method="post">
            <input name="Playlist_ID" value="<?= $reg['ID'] ?>" type="hidden">
            <div class="row">
                <div class="col-md-6">
                    <?= $reg['ID'] ?> - 
                    <?= $reg['nome'] ?>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="update" class="btn btn-danger" value="delProduto" title="Remover"></button>
                </div>

            </div>
        </form>
        <hr>
        <?php
    }
}else {
    ?>
        <div class="alert alert-info">Sem Playlists</div>
    <?php 
}   
?>
-->



<?php     

if (count($encProdutos) > 0) {
    foreach ($encProdutos as $reg) {
    ?>
<div class="container"><!--1-->
    <div class="col-md-12"><!--2-->

    <form action="?m=<?= $module ?>&a=add&id=<?= $id ?>" role="form" method="post">
            <input name="Playlist_ID" value="<?= $reg['ID'] ?>" type="hidden">

        <div class="card b-1 hover-shadow mb-20"><!--3-->
            <div class="media card-body"><!--4-->
                <div class="media-left pr-12">
                
                </div>
                <div class="media-body"><!--5-->
                    <div class="mb-2">
                        <span class="fs-20 pr-16"><?= $reg['nome'] ?></span>
                    </div>
                    <small class="fs-16 fw-300 ls-1"><?= $reg['descricao'] ?></small>
                </div><!--5-->
                <div class="media-right text-right d-none d-md-block">
                    <p class="fs-14 text-fade mb-12"><i class="#"></i><?= $reg['ID'] ?></p>
                 
                </div>
            </div><!--4-->
            <footer class="card-footer flexbox align-items-center">
                <div>
                    <strong>Criado a:</strong>
                    <span><?= $reg['data_criacao'] ?></span>
                </div>  
                <div class="col-md-2">
                    <button type="submit" name="update" class="btn btn-danger" value="delProduto" title="Remover"></button>
                    
                </div>
                <div class="card-hover-show">


                    </a>
                </div>
            </footer>
        </div><!--3-->
        <br>
    </form>
    </div><!--2-->
</div><!--1-->

<?php
   }
}else {
    ?>
        <div class="alert alert-info">Sem Playlists</div>
    <?php 
}   
?>
    





<hr>
<h5>Adicionar Playlists a Displays</h5>
<br>
<!--
    <?php     

    foreach ($novosProdutos as $reg) {
        ?>
        <form action="?m=<?= $module ?>&a=add&id=<?= $id ?>" role="form" method="post">
            <input name="Playlist_ID" value="<?= $reg['ID'] ?>" type="hidden">
            <div class="row">
                <div class="col-md-6">
                    <?= $reg['ID'] ?> -
                    <?= $reg['nome'] ?>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="update" class="btn btn-success" value="addProduto" title="Adicionar">
                    </button>
                </div>
            </div>
        </form>
        <hr>
        <?php
    }

    ?>

 <?php

?>




-->
<?php     
if (count($novosProdutos) > 0) {
foreach ($novosProdutos as $reg) {
    ?>
<div class="container"><!--1-->
    <div class="col-md-12"><!--2-->

    <form action="?m=<?= $module ?>&a=add&id=<?= $id ?>" role="form" method="post">
            <input name="Playlist_ID" value="<?= $reg['ID'] ?>" type="hidden">

        <div class="card b-1 hover-shadow mb-20"><!--3-->
            <div class="media card-body"><!--4-->
                <div class="media-left pr-12">
                
                </div>
                <div class="media-body"><!--5-->
                    <div class="mb-2">
                        <span class="fs-20 pr-16"><?= $reg['nome'] ?></span>
                    </div>
                    <small class="fs-16 fw-300 ls-1"><?= $reg['descricao'] ?></small>
                </div><!--5-->
                <div class="media-right text-right d-none d-md-block">
                    <p class="fs-14 text-fade mb-12"><i class="#"></i><?= $reg['ID'] ?></p>
                 
                </div>
            </div><!--4-->
            <footer class="card-footer flexbox align-items-center">
                <div>
                    <strong>Criado a:</strong>
                    <span><?= $reg['data_criacao'] ?></span>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="update" class="btn btn-success" value="addProduto" title="Adicionar">
                    </button>
                </div>
                <div class="card-hover-show">


                    </a>
                </div>
            </footer>
        </div><!--3-->
        <br>
    </form>
    </div><!--2-->
</div><!--1-->

<?php
   }
}else {
    ?>
        <div class="alert alert-info">Sem Playlists</div>
    <?php 
}   
?>
  
  <?php
    }
?>