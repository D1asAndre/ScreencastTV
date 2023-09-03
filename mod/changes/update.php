<?php
if (count(get_included_files()) == 1) {
    header('Location: ../../index.php');
    exit("Direct access not permitted.");
}


$TITLE =  'Editar';

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
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $avatar = filter_input(INPUT_POST, 'avatar', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $new_password = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);
    $confirm_password = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);
    $oie = filter_input(INPUT_POST, 'oie', FILTER_SANITIZE_STRING);
    $errors = false;
    
    if ($username == '') {
        $result .= '<div class="alert-danger">Tem que definir um username.</div>';
        $errors = true;
    }
   

    $sql = "SELECT * FROM users WHERE ID = ".$_SESSION['uid'];
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":ID", $id, PDO::PARAM_INT);
    $stmt->execute();

    if($password || $new_password || $confirm_password != '') {                                                
    if (password_verify($password, $oie))  {
        // Check if password is same
        if ($new_password == $confirm_password) {
            $newone = password_hash($new_password, PASSWORD_DEFAULT);
    
        } else {
            $newone = $oie;
            $result .= '<div class="alert-danger">Password não alterada. Verifique a confirmação da mesma.</div>';
        }
    } else {
        $newone = $oie;
        $result .= '<div class="alert-danger">Password não alterada. Password incorreta.</div>';
    }
    } else {
        $newone = $oie;
        
    }

    if (!$errors) {
        $sql = "UPDATE users SET 
                    username = :USERNAME,
                    password = :password,
                    avatar = :AVATAR
                WHERE id = :ID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":ID", $id, PDO::PARAM_INT);
        $stmt->bindValue(":USERNAME", $username, PDO::PARAM_STR);
        $stmt->bindValue(":AVATAR", $avatar, PDO::PARAM_STR);
        $stmt->bindValue(":password", $newone, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->execute()) {
            $result .= '<div class="alert-success">Utilizador atualizado com sucesso!</div>';
        } else {
            $result .= '<div class="alert-danger">Erro ao atualizar utilizador.</div>';
        }
    }
}

// Criar query para apresentação de dados para edição no Formulário
$sql = "SELECT * FROM users WHERE ID = ".$_SESSION['uid'];
    
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
        <a href="index.php" class="btn" title="Fechar"><i class="fi fi-close"></i></a>
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
        <label for="username">Username</label>
        <input name="username" id="username" value="<?= $reg['username'] ?>" type="text" required="" class="form-control">
    </div>
    <div class="form-group">
        <label for="avatar">Avatar</label>
        <input name="avatar" id="avatar" type="text" value="<?= $reg['avatar'] ?>" class="form-control">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input name="password" id="password" placeholder="Deixe em branco caso não pretenda alterar*" type="text" class="form-control">
    </div>
    
    <div class="form-group">
        <label for="new_password">Nova Password</label>
        <input name="new_password" id="new_password" type="text"  class="form-control">
    </div>
    <div class="form-group">
        <label for="confirm_password">Confirmar Password</label>
        <input name="confirm_password" id="confirm_password" type="text"  class="form-control">
    </div>

    <div class="form-group">
        <label for="oie"></label>
        <input name="oie" id="oie" type="text" class="oie" value="<?= $reg['password'] ?>" readonly class="form-control">
    </div>

    <style>
        .oie{
            display:none;
            visibility:hidden;
        }
    </style>
    <button type="submit" name="update" class="btn btn-primary" value="update" title="Guardar"><i class="fi fi-save"></i> Guardar</button>
    <a href="index.php" class="btn btn-secondary" title="Cancelar"> Cancelar</a>
</form>

