<?php
if (count(get_included_files()) == 1) {
    header('Location: ../../index.php');
    exit("Direct access not permitted.");
}

$TITLE = 'Mensagem - Visualizar';

$DEBUG .= "Ligar à BD\n";
$pdo = connectDB($db);

// Obter ID pelo URL de forma segura
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$DEBUG .= "Obter dados do URL: id=$id\n";

// Criar query
$sql ="SELECT * FROM messages WHERE id = :ID";

// Preparar Query
$stmt = $pdo->prepare($sql);

// Associar valor do ID
$stmt->bindValue(':ID', $id,PDO::PARAM_INT);
$DEBUG .= "Executar query: ". print_r($stmt, true)."\n";

?>
<div class="row">
    <div class="col-sm-auto"><h3><?= $TITLE ?></h3></div>
    <div class="ml-sm-auto">
        <a href="?m=<?= $module ?>&a=read" class="btn" title="Fechar"><i class="fi fi-close"></i></a>
    </div>
</div>
<div class="row">
    <div class="col-12">
<?php
    // Executar query
    if ($stmt->execute()) { 
        $reg = $stmt->fetch();
        $DEBUG .= "Obter dados da BD: " . print_r($reg, true) . "\n";
        ?>
        <div class="card">
            <div class="card-header"><h5><?= htmlspecialchars_decode($reg['assunto']) ?></h5></div>
            <div class="card-block"><?= nl2br(htmlspecialchars_decode($reg['corpo'])) ?></div>
            <div class="card-footer text-muted text-md-right"><?= $reg['data_hora'] ?></div>
        </div>
        <?php
    }else{ ?>
        <div class="alert alert-danger">Ocorreu um erro ao consultar o registo.</div>
        <?php
    }

?>
    </div>
</div>

<a href="?m=<?= $module ?>&a=read" class="btn btn-secondary" title="Voltar"> Voltar</a>