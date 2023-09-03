<?php //
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
$TITLE = 'Playlist - Adicionar';

// Verificar se foi feito submit ao formulário
$add = filter_input(INPUT_POST, 'add', FILTER_SANITIZE_STRING);
if ($add) {
    $result = '';
    $DEBUG .= "Ligar à BD\n";
    $pdo = connectDB($db);

    // Obter valores do Formulário de forma segura
    $DEBUG .= "Obter dados do Formulário:\n";
    $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_STRING);
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
    
    $sql = "INSERT INTO Playlist(ID,nome,descricao,data_criacao) VALUES(:ID, :nome, :descricao, now())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":ID", $ID, PDO::PARAM_STR);
    $stmt->bindValue(":nome", $nome, PDO::PARAM_STR);
    $stmt->bindValue(":descricao", $descricao, PDO::PARAM_STR);
    /*
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $id = $pdo->lastInsertId();
        $url = "?m=$module&a=add&id=$id";
        $result .= '<div class="alert-success">Registo adicionado com sucesso!</div>';~
        $result .= '<a href="'.$url.'">Adicionar Conteúdos</a></div>';
        // Redirecionar para Adicionar produtos à encomenda
        header("Location: $url");
        exit();
    } else {
        $result .= '<div class="alert-danger">Erro ao adicionar registo.</div>';
    }   
}
*/
if ($stmt->execute()) {
    $result .= '<div class="alert-success">Registo adicionado com sucesso!</div>';
} else {
    $result .= '<div class="alert-danger">Erro ao adicionar registo.</div>';
}   
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

<form action="?m=<?= $module ?>&a=create" role="form" method="post">
    
    <div class="form-group">
        <label for="nome">Nome</label>
        <input name="nome" id="nome" type="text" class="form-control">
    </div>

    <div class="form-group">
        <label for="descricao">Descrição</label>
        <input name="descricao" id="descricao" type="text" class="form-control">
    </div>
   
    <button type="submit" name="add" class="btn btn-primary" value="add" title="Guardar"><i class="fi fi-save"></i> Guardar</button>
    <a href="?m=<?= $module ?>&a=read" class="btn btn-secondary" title="Cancelar"> Cancelar</a>
</form>

<?php
    }
?>