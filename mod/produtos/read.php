<?php
if (count(get_included_files()) == 1) {
    header('Location: ../../index.php');
    exit("Direct access not permitted.");
}

$TITLE = 'Produtos';

$DEBUG .= "Ligar à BD\n";
$pdo = connectDB($db);

// Criar query
$sql ="SELECT * FROM products, categorias WHERE categorias.ID = products.categoria_id";
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
        <a href="?m=<?= $module ?>&a=create" class="btn btn-primary" title="Novo"><i class="fi fi-plus-a"></i> Novo</a>
    </div>
</div>
<div class="table-responsive-md">
<table class="table table-striped table-hover table-sm">
    <thead>
        <tr>
            <th class="align-middle">ID</th>
            <th class="align-middle">Nome</th>
            <th class="align-middle">Categoria</th>
            <th class="align-middle">Data Hora</th>
            <th class="align-middle">Operações</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($num > 0) {
        foreach ($list as $reg) {
            ?>
            <tr>
                <td class="align-middle"><?= $reg['id'] ?></td>
                <td class="align-middle"><?= $reg['nome'] ?></td>
                <td class="align-middle"><?= $reg['categoria_id'] ?> - <?= $reg['NOME'] ?></td>
                <td class="align-middle"><?= $reg['data_hora'] ?></td>
                <td class="align-middle">
                    <a href="?m=<?= $module ?>&a=update&id=<?= $reg['id'] ?>" title="Editar" class="btn btn-secondary">
                        <i class="fi fi-prescription"></i>  Editar
                    </a>
                    <a href="?m=<?= $module ?>&a=delete&id=<?= $reg['id'] ?>" title="Eliminar" class="btn btn-danger"
                       onclick="return confirm('Pretende eliminar o registo <?= $reg['id'] ?> - <?= $reg['nome'] ?>?');">
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