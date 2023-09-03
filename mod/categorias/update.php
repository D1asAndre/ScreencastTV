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
$TITLE = 'Displays - Editar';

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
    $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);

        $sql = "UPDATE Displays SET
                    tipo = :tipo  
                WHERE ID = :ID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":ID", $id, PDO::PARAM_INT);
        $stmt->bindValue(":tipo", $tipo, PDO::PARAM_STR);
        if ($stmt->execute()) {
            $result .= '<div class="alert-success">Display atualizado com sucesso!</div>';
        } else {
            $result .= '<div class="alert-danger">Erro ao atualizar Display.</div>';
        }
} 

// Criar query para apresentação de dados para edição no Formulário
$sql = "SELECT * FROM categorias WHERE ID = :ID LIMIT 1";

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
        <a href="?m=<?= $module ?>&a=read" class="btn" title="Fechar"><i class="fi fi-close"></i></a>
    </div>
</div>
<div class="row">
    <div class="col-12">
    <?= isset($result) ? $result : '' ?>
    </div>
</div>


<form action="?m=<?= $module ?>&a=update&id=<?= $id ?>" role="form" method="post">

    <div class="form-group">    
        <label for="id">id</label>
        <input name="id" id="id" type="text" value="<?= $reg['ID'] ?>" readonly class="form-control">
    </div>  

    <div class="form-group">
        <label for="tipo">Tipo</label>
        <input name="tipo" id="tipo" type="text" value="<?= $reg['tipo'] ?>" class="form-control">
    </div>

    <button type="submit" name="update" class="btn btn-primary" value="update" title="Guardar"><i class="fi fi-save"></i> Guardar</button>
    <a href="?m=<?= $module ?>&a=read" class="btn btn-secondary" title="Cancelar"> Cancelar</a>
</form>

<?php
    }
?>