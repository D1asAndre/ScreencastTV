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
$TITLE = 'Displays - Adicionar';

// Verificar se foi feito submit ao formulário
$add = filter_input(INPUT_POST, 'add', FILTER_SANITIZE_STRING);
if ($add) {
    $result = '';
    $DEBUG .= "Ligar à BD\n";
    $pdo = connectDB($db);

    // Obter valores do Formulário de forma segura
    $DEBUG .= "Obter dados do Formulário:\n";
    $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_STRING);
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
    $macaddress = filter_input(INPUT_POST, 'macaddress', FILTER_SANITIZE_STRING);
    $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
    $image = filter_input(INPUT_POST, 'image', FILTER_SANITIZE_STRING);

    $sql = "INSERT INTO Displays(ID,descricao,macaddress,token,image,data_criacao) VALUES(:ID, :descricao, :macaddress, :token, :image, now())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":ID", $ID, PDO::PARAM_STR);
    $stmt->bindValue(":descricao", $descricao, PDO::PARAM_STR);
    $stmt->bindValue(":macaddress", $macaddress, PDO::PARAM_STR);
    $stmt->bindValue(":token", $token, PDO::PARAM_STR);
    $stmt->bindValue(":image", $image, PDO::PARAM_STR);
    /*
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $id = $pdo->lastInsertId();
        $url = "?m=$module&a=add&id=$id";
        $result .= '<div class="alert-success">Registo adicionado com sucesso!</div>';
        $result .= '<a href="'.$url.'">Adicionar Playlists</a></div>';
        // Redirecionar para Add Playlists a Displays
        header("Location: ?m=displays&a=add&id=14");
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
        <label for="descricao">Nome</label>
        <input name="descricao" id="descricao" type="text" class="form-control">
    </div>

    <div  class="oie">
        <label for="macaddress">Endereço Mac</label>
        <input name="macaddress" id="macaddress" type="text" class="form-control">
    </div>

    <div class="form-group">
        <label for="token">Token</label>
        <input name="token" id="token" type="text" class="form-control">
    </div>
    <div class="form-group">
        <label for="token">Image</label>
        <input name="image" id="image" type="text" class="form-control">
    </div>

    <button type="submit" name="add" class="btn btn-primary" value="add" title="Guardar"><i class="fi fi-save"></i> Guardar</button>
    <a href="?m=<?= $module ?>&a=read" class="btn btn-secondary" title="Cancelar"> Cancelar</a>
</form>
<style>
        .oie{
            display:none;
            visibility:hidden;
        }
    </style>
<?php
    }
?>