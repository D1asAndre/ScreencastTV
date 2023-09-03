<?php
if (count(get_included_files()) == 1) {
    header('Location: ../../index.php');
    exit("Direct access not permitted.");
}

$TITLE = 'Produtos - Editar';

$result = '';
$DEBUG .= "Ligar à BD\n";
$pdo = connectDB($db);

// Obter ID pelo URL de forma segura
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$DEBUG .= "Obter dados do URL: id=$id\n";


// Verificar se foi feito submit ao formulário
$update = filter_input(INPUT_POST, 'update', FILTER_SANITIZE_STRING);
if ($update) {

    // Obter valores do Formulário de forma segura
    $DEBUG .= "Obter dados do Formulário:\n";
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);

    $sql = "UPDATE categorias SET 
                NOME = :NOME,
                DESCRICAO = :DESCRICAO
            WHERE ID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":ID", $id, PDO::PARAM_INT);
    $stmt->bindValue(":NOME", $nome, PDO::PARAM_STR);
    $stmt->bindValue(":DESCRICAO", $descricao, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->execute()) {
        $result .= '<div class="alert-success">Registo atualizado com sucesso!</div>';
    } else {
        $result .= '<div class="alert-danger">Erro ao atualizar registo.</div>';
    }
}

// Criar query para apresentação de dados para edição no Formulário
$sql = "SELECT * FROM products WHERE id = :ID LIMIT 1";

// Preparar Query
$stmt = $pdo->prepare($sql);

// Associar valor do ID
$stmt->bindValue(':ID', $id, PDO::PARAM_INT);

// Executar query
if ($stmt->execute()) {
    $reg = $stmt->fetch();
    $DEBUG .= "Obter dados da BD: " . print_r($reg, true) . "\n";
} else {
    $result .= '<div class="alert alert-danger">Ocorreu um erro ao ler registo da Base de Dados.</div>';
}

$sql = "SELECT * FROM categorias";
$DEBUG .= "Query SQL: $sql\n";

// Executar query e obter dados da Base de Dados
$list = $pdo->query($sql)->fetchAll();

// Contar número de registos
$num = count($list);
$DEBUG .= "Número de registos: $num\n";
?>
<div class="row">
    <div class="col-sm-auto"><h3><?= $TITLE ?></h3></div>
    <div class="ml-sm-auto">
        <a href="?m=<?= $module ?>&a=read" class="btn" title="Fechar"><i class="fi fi-close"></i></a>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <?= isset($result) ? $result : '' ?>
    </div>
</div>
<form action="?m=<?= $module ?>&a=update&id=<?= $id ?>" role="form" method="post">
    <div class="form-group">
        <label for="id">ID</label>
        <input name="id" id="id" value="<?= $reg['id'] ?>" type="text" readonly class="form-control">
    </div>
    <div class="form-group">
        <label for="nome">Nome</label>
        <input name="nome" id="nome" value="<?= $reg['nome'] ?>"  type="text" required="" class="form-control">
    </div>
    <div class="form-group">
        <label for="categoria_id">Categoria</label>
        <select name="categoria_id" id="categoria_id" class="form-control" > <?php
        foreach($list as $reg){?>
            <option value="<?= $reg['ID'] ?>" ><?= $reg['NOME'] ?></option>
            
            
            <?php
        }
            
            
            ?>
        </select>        
    </div>
    <div class="form-group">
        <label for="data_hora">Data Hora</label>
        <input name="data_hora" id="data_hora" value="<?= $reg['data_hora'] ?>"  type="text" class="form-control">
    </div>
    <button type="submit" name="update" class="btn btn-primary" value="update" title="Guardar"><i class="fi fi-save"></i> Guardar</button>
    <a href="?m=<?= $module ?>&a=read" class="btn btn-secondary" title="Cancelar"> Cancelar</a>
</form>
