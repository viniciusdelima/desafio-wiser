<?php
/**
 * Arquivo de migration para tabela dos usuários do sistema.
 */

require '../../vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable( dirname(dirname(__DIR__)));
$dotenv->load();

$pathdb = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'database.db';

$db = new \SQLite3($pathdb);
$db->enableExceptions(true);

$querySearch = "SELECT * FROM sqlite_master WHERE type='table'";
$queryCreate = "CREATE TABLE users(
    login VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL
)";

try {
    $results = $db->query($querySearch);
    $results = $results->fetchArray(SQLITE3_ASSOC);
    
    if (false === $results) {
        $db->query($queryCreate);
    }
} catch (\Exception $e) {
    throw new \Exception("Erro ao criar banco de dados.");
}