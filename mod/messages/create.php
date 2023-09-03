<?php //
if (count(get_included_files()) == 1) {
    header('Location: ../../index.php');
    exit("Direct access not permitted.");
}

$TITLE = 'Nova Mensagem';

$result = '';
$DEBUG .= "Ligar à BD\n";
$pdo = connectDB($db);

// Obter lista de destinatários
$sql ="SELECT * FROM users WHERE id <> ".$_SESSION['uid']." ORDER BY username ASC";
$DEBUG .= "Query SQL: $sql\n";

// Executar query e obter dados da Base de Dados
$list = $pdo->query($sql)->fetchAll();

// Contar número de registos
$num = count($list);
$DEBUG .= "Número de registos: $num\n";



// Verificar se foi feito submit ao formulário
$add = filter_input(INPUT_POST, 'add', FILTER_SANITIZE_STRING);
if ($add) {

    // Obter valores do Formulário de forma segura
    $DEBUG .= "Obter dados do Formulário:\n";
    $destinatario = filter_input(INPUT_POST,'destinatario', FILTER_SANITIZE_NUMBER_INT);
    $DEBUG .= "destinatário: $destinatario\n";
    $assunto = htmlspecialchars(filter_input(INPUT_POST,'assunto', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $DEBUG .= "assunto: $assunto\n";
    $corpo = htmlspecialchars(filter_input(INPUT_POST,'corpo', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $DEBUG .= "corpo: $corpo\n";
    
    $errors = false;

    if (!$errors) {
        $sql = "INSERT INTO messages(remetente,destinatario,assunto,corpo,data_hora) 
                VALUES(:REM , :DEST , :ASSUNTO , :CORPO , NOW() )";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":REM", $_SESSION['uid'], PDO::PARAM_INT);
        $stmt->bindValue(":DEST", $destinatario, PDO::PARAM_INT);
        $stmt->bindValue(":ASSUNTO", $assunto, PDO::PARAM_STR);
        $stmt->bindValue(":CORPO", $corpo, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $result .= '<div class="alert-success">Mensagem enviada com sucesso!</div>';
        } else {
            $result .= '<div class="alert-danger">Erro ao enviar mensagem.</div>';
        }
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
        <label for="destinatario">Destinatário</label>
        <select name="destinatario" id="destinatario" class="form-control">
            <?php
            foreach ($list as $dest){ ?>
            <option value="<?= $dest['id'] ?>"><?= $dest['username'] ?> &lt;<?= $dest['email'] ?>&gt;</option>
                <?php
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="assunto">Assunto</label>
        <input name="assunto" id="assunto" type="text" maxlength="50" class="form-control">
    </div>
    <div class="form-group">
        <label for="corpo">Corpo</label>
        <textarea name="corpo" id="corpo" class="form-control" rows="10"></textarea>
    </div>
    <button type="submit" name="add" class="btn btn-primary" value="add" title="Enviar"><i class="fi fi-paper-plane"></i> Enviar</button>
    <a href="?m=<?= $module ?>&a=read" class="btn btn-secondary" title="Cancelar"> Cancelar</a>
</form>