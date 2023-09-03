

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

$TITLE = 'Adicionar #'.$id;

// Verificar se foi feito submit ao formulário
$update = filter_input(INPUT_POST, 'update', FILTER_SANITIZE_STRING);

// Adicionar conteudo a playlist                                                            
if ($update == 'addProduto') {                          
    $result = '';
    // Obter valores do Formulário de forma segura
    $DEBUG .= "Obter dados do Formulário:\n";
    $Conteudo_ID = filter_input(INPUT_POST, 'Conteudo_ID', FILTER_SANITIZE_STRING);

    
    // Criar query para inserir na BD                                           ----------
    $sql = "INSERT INTO
                    Playlist_has_Conteudo(Conteudo_ID,Playlist_ID)
                    VALUES(:Conteudo_ID, :Playlist_ID)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":Conteudo_ID", $Conteudo_ID, PDO::PARAM_INT);
    $stmt->bindValue(":Playlist_ID", $id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        $result .= '<div class="alert alert-success">Produto adicionado com sucesso!</div>';
    } else {
        $result .= '<div class="alert alert-danger">Erro ao adicionar produto.</div>';
    }
}
// Remover Conteudo de playlist                                                 ----------
elseif ($update == 'delProduto') {
    $result = '';
    // Obter valores do Formulário de forma segura
    $DEBUG .= "Obter dados do Formulário:\n";
    $Conteudo_ID = filter_input(INPUT_POST, 'Conteudo_ID', FILTER_SANITIZE_NUMBER_INT);
    
    // Criar query para inserir na BD
    $sql = "DELETE FROM
                    Playlist_has_Conteudo
                    WHERE
                        Conteudo_ID = :Conteudo_ID
                        AND
                        Playlist_ID = :Playlist_ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":Conteudo_ID", $Conteudo_ID, PDO::PARAM_INT);
    $stmt->bindValue(":Playlist_ID", $id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        $result .= '<div class="alert alert-success">Produto removido com sucesso!</div>';
    } else {
        $result .= '<div class="alert alert-danger">Erro ao remover produto.</div>';
    }
}



// Criar query para apresentação dos dados da playlist
$sql = "SELECT * FROM Playlist WHERE ID = :ID LIMIT 1";
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

//----------------------------------------------------------------------------------------------------------------------------
// Obter dados para listar conteudos existentes na playlist                        
$sql ="SELECT
CO.ID, CO.titulo, CO.categorias_idTipos, CA.tipo, CO.url
FROM
    Conteudo AS CO, Playlist_has_Conteudo AS PC, categorias AS CA
WHERE
    CA.ID = CO.categorias_idTipos
    AND
    CO.ID = PC.Conteudo_ID
    AND
    PC.Playlist_ID = :ID";
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


// Obter dados para listar conteudos que nao estao na playlist
$sql ="SELECT
            CO.ID, CO.titulo, CO.categorias_idTipos, CA.tipo, CO.url
            FROM
                Conteudo AS CO, categorias AS CA
            WHERE
                CA.ID = CO.categorias_idTipos
                AND
                CO.ID NOT IN
                (SELECT Conteudo_ID FROM Playlist_has_Conteudo AS PC WHERE PC.Playlist_ID = :ID)";
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
<h5>Conteúdo na Playlist</h5>
<br>
<!--
<?php
if (count($encProdutos) > 0) {
    foreach ($encProdutos as $reg) {
        ?>
        <form action="?m=<?= $module ?>&a=add&id=<?= $id ?>" role="form" method="post">
            <input name="Conteudo_ID" value="<?= $reg['ID'] ?>" type="hidden">
            <div class="row">
                <div class="col-md-6">
                    <?= $reg['ID'] ?> - <?= $reg['titulo'] ?> 
                    |
                    <?= $reg['tipo'] ?>
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
        <div class="alert alert-info">Sem conteúdo</div>
    <?php 
}   
?>
-->
<!--------------------------------------------------------->
<div class="container-fluid"><!--1-->

    <div class="px-lg-5"><!--2-->
      <div class="row"><!--3-->
    <?php
    if (count($encProdutos) > 0) {
        foreach ($encProdutos as $reg) {
            ?>






        <!-- Gallery item -->
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4"><!--4-->

        <form action="?m=<?= $module ?>&a=add&id=<?= $id ?>" role="form" method="post">
    <input name="Conteudo_ID" value="<?= $reg['ID'] ?>" type="hidden">
          <div  id="myImg" class="bg-white rounded shadow-sm"><a href="#"> <img src="<?= $reg['url'] ?>" alt="" class="img-fluid card-img-top"><!--5-->
         


            <div class="p-4"><!--6-->
              <h5> <a href="#" class="text-dark"><?= $reg['titulo'] ?> <?= $reg['ID'] ?></a></h5>
              
              <div class="d-flex align-items-center justify-content-between rounded-pill bg-light px-3 py-2 mt-4">
                <p class="small mb-0">
            
                <span class="font-weight-bold"><?= $reg['tipo'] ?></span>
                <div class="col-md-2">
                    <button type="submit" name="update" class="btn btn-danger" value="delProduto" title="Remover">
                    <i class="fa fa-minus" aria-hidden="true"></i>
                    </button>
                    
                </div>
            </p>
                
            
              </div><!--6-->
            </div><!--5-->
            </form>
          </div><!--4-->
        </div><!--3-->
        <!-- End -->
        
  
            <?php
        }
    }else {
        ?>
            <tr>
                <td colspan="6">Sem Conteúdos</td>
            </tr>
        <?php 
    }
    ?>
 
</div><!--2-->  

</div><!--1-->
<!----------------------------------------------------------------->

<h5>Adicionar Conteúdo à Playlist</h5>
<br>
<!--
    <?php     

    foreach ($novosProdutos as $reg) {
        ?>
        <form action="?m=<?= $module ?>&a=add&id=<?= $id ?>" role="form" method="post">
            <input name="Conteudo_ID" value="<?= $reg['ID'] ?>" type="hidden">
            <div class="row">
                <div class="col-md-6">
                    <?= $reg['ID'] ?> - <?= $reg['titulo'] ?>
                    |
                    <?= $reg['tipo'] ?>
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
-->




<div class="container-fluid"><!--1-->


    <div class="px-lg-5"><!--2-->
      <div class="row"><!--3-->
      
    <?php
   if (count($novosProdutos) > 0) {
        foreach ($novosProdutos as $reg) {
            ?>

   



        <!-- Gallery item -->
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4"><!--4-->

        <form action="?m=<?= $module ?>&a=add&id=<?= $id ?>" role="form" method="post">
            <input name="Conteudo_ID" value="<?= $reg['ID'] ?>" type="hidden">

          <div  id="myImg" class="bg-white rounded shadow-sm"><a href="#"> <img src="<?= $reg['url'] ?>" alt="" class="img-fluid card-img-top"><!--5-->
         


            <div class="p-4"><!--6-->
              <h5> <a href="#" class="text-dark"><?= $reg['titulo'] ?> <?= $reg['ID'] ?></a></h5>
              
              <div class="d-flex align-items-center justify-content-between rounded-pill bg-light px-3 py-2 mt-4">
                <p class="small mb-0">
            
                <span class="font-weight-bold"><?= $reg['tipo'] ?></span>
                <div class="col-md-2">
                    <button type="submit" name="update" class="btn btn-success" value="addProduto" title="Adicionar">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                </div>
            </p>
                
            
              </div><!--6-->
            </div><!--5-->
            </form>
          </div><!--4-->
        </div><!--3-->
        <!-- End -->
        
  
            <?php
        }
    }else {
        ?>
         <div class="alert alert-info">Sem Conteúdo</div>
    <?php 
}
           ?>
 
</div><!--2-->  

</div><!--1-->
          
<?php
    }
?>