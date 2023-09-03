<?php
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
$TITLE = 'Utilizadores';

$DEBUG .= "Ligar à BD\n";
$pdo = connectDB($db);

// Criar query

//$sql ="SELECT * FROM users where ID = $_SESSION['ID']";
//$sql ="SELECT * FROM users"; //para todos os users
//$sql = "SELECT * FROM users WHERE ID = 2";

                                    //'".$_SESSION['username']."'
$sql = "SELECT * FROM users WHERE ID = ".$_SESSION['uid'];
$DEBUG .= "Query SQL: $sql\n";

// Executar query e obter dados da Base de Dados    x\
$list = $pdo->query($sql)->fetchAll();

// Contar número de registos
$num = count($list);
$DEBUG .= "Número de registos: $num\n";
    

?>
<div class="row">
    <div class="col-sm-auto">
        <h3><?= $TITLE ?></h3>
    </div>

</div>
<div class="table-responsive-md">
<table class="table table-striped table-hover table-sm">
    <thead>
        <tr>
            <th class="align-middle">ID</th>
            <th class="align-middle">Username</th>
            <th class="align-middle">Email</th>
            <th class="align-middle">Perfil</th>
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
                <td class="align-middle"><?= $reg['username'] ?></td>
                <td class="align-middle"><?= $reg['email'] ?></td>
                <td class="align-middle"><?= $reg['profile'] ?></td>
                <td class="align-middle">
                    <a href="?m=<?= $module ?>&a=update&id=<?= $reg['id'] ?>" title="Editar" class="btn btn-secondary">
                        <i class="fi fi-prescription"></i>  Editar
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
<?php
    }
?>