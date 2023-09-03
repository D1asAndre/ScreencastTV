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
$TITLE = 'Displays - Eliminar';

$DEBUG .= "Ligar à BD\n";
$pdo = connectDB($db);

// Obter ID pelo URL de forma segura
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$DEBUG .= "Obter dados do URL: id=$id\n";

// Criar query
$sql ="DELETE FROM Playlist_has_Displays WHERE Displays_ID = :ID";

// Preparar Query
$stmt = $pdo->prepare($sql);

// Associar valor do ID
$stmt->bindValue(':ID', $id,PDO::PARAM_INT);
$DEBUG .= "Executar query: ". print_r($stmt, true)."\n";

// Executar query
if ($stmt->execute()) {
    $result = '<div class="alert alert-success">Playlist desassociada do Display.</div>';
    // Criar query
    $sql ="DELETE FROM Displays WHERE ID = :ID";

    // Preparar Query
    $stmt = $pdo->prepare($sql);

    // Associar valor do ID
    $stmt->bindValue(':ID', $id,PDO::PARAM_INT);
    $DEBUG .= "Executar query: ". print_r($stmt, true)."\n";

    // Executar query
    if ($stmt->execute()) {
        $result .= '<div class="alert alert-success">Display removido com sucesso.</div>';


    }else{
        $result .= '<div class="alert alert-danger">Erro ao remover Display.</div>';
    }


}else{
    $result = '<div class="alert alert-danger">Erro ao desassociar Playlist.</div>';
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

<a href="?m=<?= $module ?>&a=read" class="btn btn-secondary" title="Voltar"> Voltar</a>

<?php
    }
?>