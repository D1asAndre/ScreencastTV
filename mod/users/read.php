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
$sql ="SELECT * FROM users";
$DEBUG .= "Query SQL: $sql\n";

// Executar query e obter dados da Base de Dados
$list = $pdo->query($sql)->fetchAll();

// Contar número de registos
$num = count($list);
$DEBUG .= "Número de registos: $num\n";


?>

<head>
  	<title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="img/minilogo2.png">
  	<link rel="icon" type="image/png" sizes="96x96" href="img/minilogo2.png">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/estilo.css" rel="stylesheet">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="css/style.css">
    <link href="css/fontisto-3.0.4/fontisto.css" rel="stylesheet" type="text/css">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,500i,600,600i,700,700i|Playfair+Display:400,400i,500,500i,600,600i,700,700i,900,900i" rel="stylesheet">

<!-- Vendor CSS Files -->

  </head>

<div class="row">
    <div class="col-sm-auto">
        <h3><?= $TITLE ?></h3>
    </div>
    <div class="ml-sm-auto">
        <a href="?m=<?= $module ?>&a=create" class="btn btn-primary" title="Novo"><i class="fi fi-plus-a"></i> Novo</a>
    </div>
</div>
<!--<
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
                        <?php
                        if ($reg['id']!=1){
                        ?>
                        <a href="?m=<?= $module ?>&a=delete&id=<?= $reg['id'] ?>" title="Eliminar" class="btn btn-danger"
                        onclick="return confirm('Pretende eliminar o registo <?= $reg['id'] ?> - <?= $reg['username'] ?>?');">
                            <i class="fi fi-trash"></i>
                        </a>
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
                    <td colspan="6">Sem registos</td>
                </tr>
            <?php 
        }
        ?>
        </tbody>
    </table>
    </div>
    -->



<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
<br>
<div class="container bootstrap snippets bootdey">
    <div class="row">
        <div class="col-lg-12">
            <div class="main-box no-header clearfix">
                <div class="main-box-body clearfix">
                    <div class="table-responsive">
                        <table class="table user-list">
                            <thead>
                                <tr>
                                <th><span>User</span></th>
                                <!--
                                <th><span>Data Criação</span></th>
                                
                                <th class="text-center"><span>Status</span></th>
    -->
                                <th><span>Email</span></th>
                                <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php
                            if ($num > 0) {
                                foreach ($list as $reg) {
                            ?>
                                <tr>
                                    <td>
                                        <img src="<?= $reg['avatar'] ?>" alt="">
                                        <a href="?m=<?= $module ?>&a=update&id=<?= $reg['id'] ?>" class="user-link"><?= $reg['username'] ?></a>
                                        <span class="user-subhead"><?= $reg['profile'] ?></span>
                                    </td>
                                    <!--
                                    <td>2013/08/12</td>
                                
                                    <td class="text-center">
                                        <span class="label label-default">pending</span>
                                    </td>
                                -->
                                    <td>
                                        <a href="#"><?= $reg['email'] ?></a>
                                    </td>
                                    <td style="width: 20%;">

                                    <a href="?m=<?= $module ?>&a=update&id=<?= $reg['id'] ?>" title="Editar" class="btn btn-outline-dark">
                                    <i class="bi-pencil-square"></i>  Editar
                                    </a>
                                    <?php
                                    
                    if ($reg['id']!=1){
                    ?>
                    <a href="?m=<?= $module ?>&a=delete&id=<?= $reg['id'] ?>" title="Eliminar" class="btn btn-danger"
                       onclick="return confirm('Pretende eliminar o registo <?= $reg['id'] ?> - <?= $reg['username'] ?>?');">
                        <i class="fi fi-trash"></i>
                    </a>
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
                <td colspan="6">Sem registos</td>
            </tr>
        <?php 
    }
    ?>
    </td>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    
.main-box.no-header {
    padding-top: 20px;
}
.main-box {
    background: #FFFFFF;
    -webkit-box-shadow: 1px 1px 2px 0 #CCCCCC;
    -moz-box-shadow: 1px 1px 2px 0 #CCCCCC;
    -o-box-shadow: 1px 1px 2px 0 #CCCCCC;
    -ms-box-shadow: 1px 1px 2px 0 #CCCCCC;
    box-shadow: 1px 1px 2px 0 #CCCCCC;
    margin-bottom: 16px;
    -webikt-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
}
.table a.table-link.danger {
    color: #e74c3c;
}
.label {
    border-radius: 3px;
    font-size: 0.875em;
    font-weight: 600;
}
.user-list tbody td .user-subhead {
    font-size: 0.875em;
    font-style: italic;
}
.user-list tbody td .user-link {
    display: block;
    font-size: 1.25em;
    padding-top: 3px;
    margin-left: 60px;
}
a {
    color: black;
    outline: none!important;
}
.user-list tbody td>img {
    position: relative;
    max-width: 50px;
    float: left;
    margin-right: 15px;
}

.table thead tr th {
    text-transform: uppercase;
    font-size: 0.875em;
}
.table thead tr th {
    border-bottom: 2px solid #e7ebee;
}
.table tbody tr td:first-child {
    font-size: 1.125em;
    font-weight: 300;
}
.table tbody tr td {
    font-size: 0.875em;
    vertical-align: middle;
    
    padding: 12px 8px;
}
a:hover{
text-decoration:none;
}
</style>



<?php
    }
?>