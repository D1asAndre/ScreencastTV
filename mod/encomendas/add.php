<?php //
if (count(get_included_files()) == 1) {
    header('Location: ../../index.php');
    exit("Direct access not permitted.");
}

$DEBUG .= "Ligar à BD\n";
$pdo = connectDB($db);

// Obter ID pelo URL de forma segura
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$DEBUG .= "Obter dados do URL: id=$id\n";

$TITLE = 'Encomenda #'.$id;

// Verificar se foi feito submit ao formulário
$update = filter_input(INPUT_POST, 'update', FILTER_SANITIZE_STRING);

// Adicionar Produto a Encomenda
if ($update == 'addProduto') {
    $result = '';
    // Obter valores do Formulário de forma segura
    $DEBUG .= "Obter dados do Formulário:\n";
    $idProduto = filter_input(INPUT_POST, 'idProduto', FILTER_SANITIZE_NUMBER_INT);
    $quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_SANITIZE_NUMBER_INT);
    
    // Criar query para inserir na BD
    $sql = "INSERT INTO
                    ENCOMENDAS_has_PRODUTOS(PRODUTOS_ID,ENCOMENDAS_ID,QUANTIDADE)
                    VALUES(:PRODUTOS_ID, :ENCOMENDAS_ID, :QUANTIDADE)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":PRODUTOS_ID", $idProduto, PDO::PARAM_INT);
    $stmt->bindValue(":ENCOMENDAS_ID", $id, PDO::PARAM_INT);
    $stmt->bindValue(":QUANTIDADE", $quantidade, PDO::PARAM_INT);
    if ($stmt->execute()) {
        $result .= '<div class="alert alert-success">Produto adicionado com sucesso!</div>';
    } else {
        $result .= '<div class="alert alert-danger">Erro ao adicionar produto.</div>';
    }
}
// Remover Produto da Encomenda
elseif ($update == 'delProduto') {
    $result = '';
    // Obter valores do Formulário de forma segura
    $DEBUG .= "Obter dados do Formulário:\n";
    $idProduto = filter_input(INPUT_POST, 'idProduto', FILTER_SANITIZE_NUMBER_INT);
    
    // Criar query para inserir na BD
    $sql = "DELETE FROM
                    ENCOMENDAS_has_PRODUTOS
                    WHERE
                        PRODUTOS_ID = :PRODUTOS_ID
                        AND
                        ENCOMENDAS_ID = :ENCOMENDAS_ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":PRODUTOS_ID", $idProduto, PDO::PARAM_INT);
    $stmt->bindValue(":ENCOMENDAS_ID", $id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        $result .= '<div class="alert alert-success">Produto removido com sucesso!</div>';
    } else {
        $result .= '<div class="alert alert-danger">Erro ao remover produto.</div>';
    }
}
// Finalizar Encomenda
elseif ($update == 'finalizar') {
    $result = '';
    
    // Criar query para finalizar na BD
    $sql = "UPDATE ENCOMENDAS SET 
                ESTADO = 'Finalizada',
                ATUALIZADO = NOW()
            WHERE ID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":ID", $id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        $result .= '<div class="alert alert-success">Encomenda finalizada com sucesso!</div>';
    } else {
        $result .= '<div class="alert alert-danger">Erro ao finalizar encomenda.</div>';
    }
}

// Criar query para apresentação dos dados da Encomenda
$sql = "SELECT * FROM ENCOMENDAS WHERE ID = :ID LIMIT 1";
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

// Obter dados para listar produtos existentes na Encomenda
$sql ="SELECT
            P.ID, P.NOME, P.CATEGORIAS_ID, C.CATEGORIA, E.QUANTIDADE
            FROM
                PRODUTOS AS P, CATEGORIAS AS C, ENCOMENDAS_has_PRODUTOS AS E
            WHERE
                C.ID = P.CATEGORIAS_ID
                AND
                P.ID = E.PRODUTOS_ID
                AND
                E.ENCOMENDAS_ID = :ID";
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


// Obter dados para listar produtos
$sql ="SELECT
            P.ID, P.NOME, P.CATEGORIAS_ID, C.CATEGORIA
            FROM
                PRODUTOS AS P, CATEGORIAS AS C
            WHERE
                C.ID = P.CATEGORIAS_ID
                AND
                P.ID NOT IN
                (SELECT PRODUTOS_ID FROM ENCOMENDAS_has_PRODUTOS AS E WHERE E.ENCOMENDAS_ID = :ID)";
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
<div class="row">
    <div class="col-sm-auto">Data: <?= $enc['DATA_HORA'] ?></div>
    <div class="col-md-auto">Morada: <?= $enc['MORADA'] ?></div>
</div>
<div class="row">
    <div class="col-12">
    <?= isset($result) ? $result : '' ?>
    </div>
</div>
<hr>
<h5>Produtos na Encomenda</h5>
<?php
if (count($encProdutos) > 0) {
    foreach ($encProdutos as $reg) {
        ?>
        <form action="?m=<?= $module ?>&a=add&id=<?= $id ?>" role="form" method="post">
            <input name="idProduto" value="<?= $reg['ID'] ?>" type="hidden">
            <div class="row">
                <div class="col-md-6">
                    <?= $reg['ID'] ?> - <?= $reg['NOME'] ?>
                </div>
                <div class="col-md-2">
                    <?= $reg['CATEGORIA'] ?>
                </div>
                <div class="col-md-2">
                    <?= $reg['QUANTIDADE'] ?> unidade(s)
                </div>
                <?php
                if($enc['ESTADO']=='Criada'){
                    ?>
                <div class="col-md-2">
                    <button type="submit" name="update" class="btn btn-danger" value="delProduto" title="Remover"><i class="fi fi-shopping-basket-remove"></i></button>
                </div>
                <?php
                }
                ?>
            </div>
        </form>
        <?php
    }
}else {
    ?>
        <div class="alert alert-info">Sem produtos</div>
    <?php 
}
?>

<hr>
<h5>Adicionar Produtos à Encomenda</h5>
<?php
if($enc['ESTADO']!='Criada'){
    ?>
        <div class="alert alert-info">Estado de Encomenda não permite adicionar Produtos.</div>
    <?php     
}elseif (count($novosProdutos) > 0) {
    foreach ($novosProdutos as $reg) {
        ?>
        <form action="?m=<?= $module ?>&a=add&id=<?= $id ?>" role="form" method="post">
            <input name="idProduto" value="<?= $reg['ID'] ?>" type="hidden">
            <div class="row">
                <div class="col-md-6">
                    <?= $reg['ID'] ?> - <?= $reg['NOME'] ?>
                </div>
                <div class="col-md-2">
                    <?= $reg['CATEGORIA'] ?>
                </div>
                <div class="col-md-2">
                    Quantidade: <input name="quantidade" value="1" type="number"  class="form-control">
                </div>
                <div class="col-md-2">
                    <button type="submit" name="update" class="btn btn-success" value="addProduto" title="Adicionar">
                        <i class="fi fi-shopping-basket-add"></i></button>
                </div>
            </div>
        </form>
        <hr>
        <?php
    }
}else {
    ?>
        <div class="alert alert-info">Sem produtos</div>
    <?php 
}

if(count($encProdutos)>0 && $enc['ESTADO']=='Criada'){
?>
<hr>
<h5>Finalizar Encomenda</h5>
<form action="?m=<?= $module ?>&a=add&id=<?= $id ?>" role="form" method="post">
    <button type="submit" name="update" class="btn btn-success" value="finalizar" title="Finalizar Encomenda">
        <i class="fi fi-shopping-basket"></i> Finalizar Encomenda</button>
</form>
<?php
}
?>