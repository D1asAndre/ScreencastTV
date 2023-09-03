<?php
if (count(get_included_files()) == 1) {
    header('Location: ../../index.php');
    exit("Direct access not permitted.");
}

$TITLE = 'Mensagens';

$DEBUG .= "Ligar à BD\n";
$pdo = connectDB($db);

// Criar query
$sql ="SELECT messages.id,assunto,users.username,data_hora FROM messages,users WHERE remetente = users.id AND destinatario = ".$_SESSION['uid'];
$DEBUG .= "Query SQL: $sql\n";

// Executar query e obter dados da Base de Dados
$recebidas = $pdo->query($sql)->fetchAll();

// Contar número de registos
$DEBUG .= "Recebidas: ".count($recebidas)."\n";

// Criar query
$sql ="SELECT messages.id,`assunto`,`data_hora`,users.username,users.email FROM messages,users WHERE messages.destinatario = users.id AND remetente = ".$_SESSION['uid'];
$DEBUG .= "Query SQL: $sql\n";

// Executar query e obter dados da Base de Dados
$enviadas = $pdo->query($sql)->fetchAll();

// Contar número de registos
$DEBUG .= "Enviadas: ".count($enviadas)."\n";

?>
<div class="row">
    <div class="col-sm-auto">
        <h3><?= $TITLE ?></h3>
    </div>
    <div class="ml-sm-auto">
        <a href="?m=<?= $module ?>&a=create" class="btn btn-primary" title="Novo"><i class="fi fi-plus-a"></i> Novo</a>
    </div>
</div>
<div class="table-responsive-md">
    <h5>Caixa de Entrada</h5>
<table class="table table-striped table-hover table-sm">
    <thead>
        <tr>
            <th class="align-middle">ID</th>
            <th class="align-middle">Assunto</th>
            <th class="align-middle">Remetente</th>
            <th class="align-middle">Data</th>
            <th class="align-middle">Operações</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if (count($recebidas) > 0) {
        foreach ($recebidas as $reg) {
            ?>
            <tr>
                <td class="align-middle"><?= $reg['id'] ?></td>
                <td class="align-middle"><?= $reg['assunto'] ?></td>
                <td class="align-middle"><?= $reg['username'] ?></td>
                <td class="align-middle"><?= $reg['data_hora'] ?></td>
                <td class="align-middle">
                    <a href="?m=<?= $module ?>&a=view&id=<?= $reg['id'] ?>" title="Visualizar" class="btn btn-primary">
                        <i class="fi fi-eye"></i>  Visualizar
                    </a>
                    <a href="?m=<?= $module ?>&a=delete&id=<?= $reg['id'] ?>" title="Eliminar" class="btn btn-danger"
                       onclick="return confirm('Pretende eliminar o registo <?= $reg['id'] ?> - <?= $reg['assunto'] ?>?');">
                        <i class="fi fi-trash"></i>
                    </a>
                </td>
            </tr>
            <?php
        }
    }else {
        ?>
            <tr>
                <td colspan="6">Sem registos</td>
            </tr>
        <?php 
    }
    ?>
    </tbody>
</table>
    <hr>
    <h5>Enviadas</h5>
<table class="table table-striped table-hover table-sm">
    <thead>
        <tr>
            <th class="align-middle">ID</th>
            <th class="align-middle">Assunto</th>
            <th class="align-middle">Destinatário</th>
            <th class="align-middle">Data</th>
            <th class="align-middle">Operações</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if (count($enviadas) > 0) {
        foreach ($enviadas as $reg) {
            ?>
            <tr>
                <td class="align-middle"><?= $reg['id'] ?></td>
                <td class="align-middle"><?= $reg['assunto'] ?></td>
                <td class="align-middle"><?= $reg['username'] ?> &lt;<?= $reg['email'] ?>&gt;</td>
                <td class="align-middle"><?= $reg['data_hora'] ?></td>
                <td class="align-middle">
                    <a href="?m=<?= $module ?>&a=view&id=<?= $reg['id'] ?>" title="Visualizar" class="btn btn-primary">
                        <i class="fi fi-eye"></i>  Visualizar
                    </a>
                    <a href="?m=<?= $module ?>&a=delete&id=<?= $reg['id'] ?>" title="Eliminar" class="btn btn-danger"
                       onclick="return confirm('Pretende eliminar o registo <?= $reg['id'] ?> - <?= $reg['assunto'] ?>?');">
                        <i class="fi fi-trash"></i>
                    </a>
                </td>
            </tr>
            <?php
        }
    }else {
        ?>
            <tr>
                <td colspan="6">Sem registos</td>
            </tr>
        <?php 
    }
    ?>
    </tbody>
</table>    
</div>