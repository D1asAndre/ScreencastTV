<?php

/* Descrição: Funções core da aplicação
 * Autor: Mário Pinto
 * 
 */

/**
 * Faz a ligação à Base de Dados
 * @param Array $db 
 *        Array com definição de host, dbname, port, charset, username e password
 * @return PDO Objeto PDO com a ligação à Base de Dados
 */
function connectDB($db) {
    try {
        $pdo = new PDO(
                'mysql:host=' . $db['host'] . '; ' .
                'port=' . $db['port'] . ';' .
                'charset=' . $db['charset'] . ';' .
                'dbname=' . $db['dbname'] . ';',
                $db['username'], $db['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    } catch (PDOException $e) {
        die('Erro ao ligar ao servidor ' . $e->getMessage());
    }
    return $pdo;
}

/**
 * Verifica se o modo DEBUG está definido e ativo
 * @return boolean
 */
function debug(){
    if(defined('DEBUG') && DEBUG){
        return true;
    }
    return false;
}

/**
 * Verifica se a variável de sessão username é admin
 * @return boolean
 */
function is_admin(){
    if (isset($_SESSION['profile']) && $_SESSION['profile']=='admin'){
        return true;
    }else{
        return false;
    }
    
}

/**
 * Verifica se a variável de sessão username é admin
 * @return boolean
 */
function is_user(){
    if (isset($_SESSION['profile']) && $_SESSION['profile']=='user'){
        return true;
    }else{
        return false;
    }
    
}