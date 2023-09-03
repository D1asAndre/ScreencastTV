<?php
if (count(get_included_files()) == 1) {
    header('Location: ../../index.php');
    exit("Direct access not permitted.");
}

$TITLE = 'Encomendas';
$DEBUG .= "Ligar à BD\n";
$pdo = connectDB($db);

// Criar query
$sql ="SELECT * FROM ENCOMENDAS ORDER BY DATA_HORA DESC";
$DEBUG .= "Query SQL: $sql\n";

// Executar query e obter dados da Base de Dados
$list = $pdo->query($sql)->fetchAll();

// Contar número de registos
$num = count($list);
$DEBUG .= "Número de registos: $num\n";


?>
<div class="row">
    <div class="col-sm-auto">
        <h3><?= $TITLE ?></h3>
    </div>
    <div class="ml-sm-auto">
        <a href="?m=<?= $module ?>&a=create" class="btn btn-primary" title="Criar Encomenda"><i class="fi fi-plus-a"></i> Criar</a>
    </div>
</div>
<div class="table-responsive-md">
<table class="table table-striped table-hover table-sm">
    <thead>
        <tr>
            <th class="align-middle">ID</th>
            <th class="align-middle">Data</th>
            <th class="align-middle">Total</th>
            <th class="align-middle">Estado</th>
            <th class="align-middle">Atualizado</th>
            <th class="align-middle">Utilizador</th>
            <th class="align-middle">Operações</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($num > 0) {
        foreach ($list as $reg) {
            ?>
            <tr>
                <td class="align-middle"><?= $reg['ID'] ?></td>
                <td class="align-middle"><?= $reg['DATA_HORA'] ?></td>
                <td class="align-middle"><?= $reg['TOTAL'] ?></td>
                <td class="align-middle"><?= $reg['ESTADO'] ?></td>
                <td class="align-middle"><?= $reg['ATUALIZADO'] ?></td>
                <td class="align-middle"><?= $reg['USERS_ID'] ?></td>
                <td class="align-middle">
                    <a href="?m=<?= $module ?>&a=add&id=<?= $reg['ID'] ?>" class="btn btn-warning" title="Ver Detalhes"> 
                        <i class="fi fi-eye"></i> </a>
                    <?php
                    if ($reg['ESTADO'] != 'Cancelada'){
                    ?>
                    <a href="?m=<?= $module ?>&a=update&id=<?= $reg['ID'] ?>" title="Editar" class="btn btn-secondary">
                        <i class="fi fi-prescription"></i>  Editar</a>
                    <a href="?m=<?= $module ?>&a=cancel&id=<?= $reg['ID'] ?>" title="Cancelar" class="btn btn-danger"
                       onclick="return confirm('Pretende cancelar a encomenda <?= $reg['ID'] ?> feita em <?= $reg['DATA_HORA'] ?>?');">
                        <i class="fi fi-close"></i> </a>
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <?php
        }
    }else {
        ?>
            <tr>
                <td colspan="7">Sem registos</td>
            </tr>
        <?php 
    }
    ?>
    </tbody>
</table>
</div>