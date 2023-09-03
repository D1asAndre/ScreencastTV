<?php //
if (count(get_included_files()) == 1) {
    header('Location: ../../index.php');
    exit("Direct access not permitted.");
}

$TITLE = 'Produtos - Adicionar';
$pdo = connectDB($db);

// Verificar se foi feito submit ao formulário
$add = filter_input(INPUT_POST, 'add', FILTER_SANITIZE_STRING);
if ($add) {
    $result = '';
    $DEBUG .= "Ligar à BD\n";

    // Obter valores do Formulário de forma segura
    $DEBUG .= "Obter dados do Formulário:\n";
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $categoria_id = filter_input(INPUT_POST, 'categoria_id', FILTER_SANITIZE_STRING);
    
    // Criar query para isnerir na BD
    $sql = "INSERT INTO products(nome,categoria_id,data_hora) VALUES(:NOME, :CATEGORIA_ID, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":NOME", $nome, PDO::PARAM_STR);
    $stmt->bindValue(":CATEGORIA_ID", $categoria_id, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $result .= '<div class="alert-success">Registo adicionado com sucesso!</div>';
    } else {
        $result .= '<div class="alert-danger">Erro ao adicionar registo.</div>';
    }
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

<form action="?m=<?= $module ?>&a=create" role="form" method="post">
    <div class="form-group">
        <label for="nome">Nome</label>
        <input name="nome" id="nome" type="text" required="" class="form-control">
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
    <button type="submit" name="add" class="btn btn-primary" value="add" title="Guardar"><i class="fi fi-save"></i> Guardar</button>
    <a href="?m=<?= $module ?>&a=read" class="btn btn-secondary" title="Cancelar"> Cancelar</a>
</form>