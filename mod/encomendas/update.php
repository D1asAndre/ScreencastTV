<?php
if (count(get_included_files()) == 1) {
    header('Location: ../../index.php');
    exit("Direct access not permitted.");
}

$TITLE = 'Encomendas - Editar';

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
    $morada = filter_input(INPUT_POST, 'morada', FILTER_SANITIZE_STRING);

    $sql = "UPDATE ENCOMENDAS SET 
                MORADA = :MORADA,
                ATUALIZADO = NOW()
            WHERE ID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":ID", $id, PDO::PARAM_INT);
    $stmt->bindValue(":MORADA", $morada, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->execute()) {
        $result .= '<div class="alert alert-success">Registo atualizado com sucesso!</div>';
    } else {
        $result .= '<div class="alert alert-danger">Erro ao atualizar registo.</div>';
    }
}

// Criar query para apresentação de dados para edição no Formulário
$sql = "SELECT * FROM ENCOMENDAS WHERE ID = :ID LIMIT 1";

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
        <input name="id" id="id" value="<?= $reg['ID'] ?>" type="text" readonly class="form-control">
    </div>
    <div class="form-group">
        <label for="morada">Morada</label>
        <input name="morada" id="morada" value="<?= $reg['MORADA'] ?>" type="text" class="form-control">
    </div>
    <div class="form-group">
        <label for="users_id">Utilizador</label>
        <input name="users_id" id="users_id" value="<?= $reg['USERS_ID'] ?>" type="text" readonly class="form-control">
    </div>

    <button type="submit" name="update" class="btn btn-primary" value="update" title="Guardar"><i class="fi fi-save"></i> Guardar</button>
    <a href="?m=<?= $module ?>&a=read" class="btn btn-secondary" title="Cancelar"> Cancelar</a>
    <a href="?m=<?= $module ?>&a=add&id=<?= $id ?>" class="btn btn-warning" title="Ver Detalhes"> Ver Detalhes</a>
</form>
