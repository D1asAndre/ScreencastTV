<?php
define('DESC', 'Instalar uma Base de Dados');
$html = '';

require_once '../config.php';
require_once '../core.php';
$pdo = connectDB($db);

$html .= '<p>Utilizador: <code>' . $db['username'] . '</code> | Base de Dados: <code>' . $db['dbname'] . '</code></p>';

$code = filter_input(INPUT_POST, 'code',FILTER_SANITIZE_STRING);

if ($code == 'install') {
    $filePath = 'schema.sql';
    $html .='<p>A abrir ficheiro <code>'.$filePath.'</code></p>';
    $res = importSqlFile($pdo, $filePath);
    if ($res === false) {
        die('ERRO: Erro ao instalar Base de Dados');
    } else {
        $html .= '<div class="alert-success">Base de Dados criada com sucesso.</div>';
    }
}

/**
 * Import SQL File
 * 
 * @param $pdo
 * @param $sqlFile
 * @param null $tablePrefix
 * @param null $InFilePath
 * @return bool
 */
function importSqlFile($pdo, $sqlFile, $tablePrefix = null, $InFilePath = null) {
    try {

        // Enable LOAD LOCAL INFILE
        $pdo->setAttribute(\PDO::MYSQL_ATTR_LOCAL_INFILE, true);

        $errorDetect = false;

        // Temporary variable, used to store current query
        $tmpLine = '';

        // Read in entire file
        $lines = file($sqlFile);

        // Loop through each line
        foreach ($lines as $line) {
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || trim($line) == '') {
                continue;
            }

            // Read & replace prefix
            $line = str_replace(['<<prefix>>', '<<InFilePath>>'], [$tablePrefix, $InFilePath], $line);

            // Add this line to the current segment
            $tmpLine .= $line;

            // If it has a semicolon at the end, it's the end of the query
            if (substr(trim($line), -1, 1) == ';') {
                try {
                    // Perform the Query
                    $pdo->exec($tmpLine);
                } catch (\PDOException $e) {
                    echo "<br><pre>Error performing Query: '<strong>" . $tmpLine . "</strong>': " . $e->getMessage() . "</pre>\n";
                    $errorDetect = true;
                }

                // Reset temp variable to empty
                $tmpLine = '';
            }
        }

        // Check if error is detected
        if ($errorDetect) {
            return false;
        }
    } catch (\Exception $e) {
        echo "<br><pre>Exception => " . $e->getMessage() . "</pre>\n";
        return false;
    }

    return true;
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title><?= SUBJ . ' | ' . DESC . ' | ' . AUTHOR ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <h3><?= DESC ?></h3>
            <div><?= $html ?></div>
            <div>
                <form action="?" method="POST">
                    <div class="alert-danger">Atenção! Este processo irá processar o ficheiro schema.sql na sua base de dados.</div>
                    <label for="code">Código:</label>
                    <input type="password" name="code" id="code" />
                    <input type="submit" name="install" class="btn btn-primary" value="Instalar">
                </form>
            </div>
            <?= '<div><code>POST: ' . print_r($_POST, true) . '<br>GET: ' . print_r($_GET, true) . '</code></div>' ?>
        </div>
    </body>
</html>