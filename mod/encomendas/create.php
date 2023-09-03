<?php //
if (count(get_included_files()) == 1) {
    header('Location: ../../index.php');
    exit("Direct access not permitted.");
}

$TITLE = 'Encomendas - Criar';
$DEBUG .= "Ligar à BD\n";
$pdo = connectDB($db);

// Verificar se foi feito submit ao formulário
$add = filter_input(INPUT_POST, 'add', FILTER_SANITIZE_STRING);
if ($add) {
    $result = '';

    // Obter valores do Formulário de forma segura
    $DEBUG .= "Obter dados do Formulário:\n";
    $morada = filter_input(INPUT_POST, 'morada', FILTER_SANITIZE_STRING);
    $users_id = filter_input(INPUT_POST, 'users_id', FILTER_SANITIZE_STRING);
    
    // Criar query para inserir na BD
    $sql = "INSERT INTO
                    ENCOMENDAS(DATA_HORA, TOTAL, MORADA, ESTADO, ATUALIZADO, USERS_ID)
                    VALUES(NOW(), 0, :MORADA, 'Criada', NOW(), :USERS_ID)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":MORADA", $morada, PDO::PARAM_STR);
    $stmt->bindValue(":USERS_ID", $users_id, PDO::PARAM_STR);
    if ($stmt->execute()) {
        // Obter ID da encomenda adicionada
        $id = $pdo->lastInsertId();
        $url = "?m=$module&a=add&id=$id";
        $result .= '<div class="alert alert-success">Encomenda criada com sucesso! ';
        $result .= '<a href="'.$url.'">Adicionar Produtos</a></div>';
        // Redirecionar para Adicionar produtos à encomenda
        header("Location: $url");
        exit();
    } else {
        $result .= '<div class="alert alert-danger">Erro ao criar encomenda.</div>';
    }
}

$sql = "SELECT ID,USERNAME FROM USERS";
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
        <label for="morada">Morada</label>
        <input name="morada" id="morada" type="text" class="form-control">
    </div>
    <div class="form-group">
        <label for="users_id">Utilizador</label>
        <select name="users_id" id="users_id" class="form-control" >
        <?php
        // Percorrer lista de Categorias e criar um OPTION para cada uma
        foreach($list as $reg){?>
            <option value="<?= $reg['ID'] ?>" ><?= $reg['USERNAME'] ?></option>
        <?php
        }
        ?>
        </select>
    </div>
    <button type="submit" name="add" class="btn btn-primary" value="add" title="Guardar"><i class="fi fi-save"></i> Guardar</button>
    <a href="?m=<?= $module ?>&a=read" class="btn btn-secondary" title="Cancelar"> Cancelar</a>
</form>