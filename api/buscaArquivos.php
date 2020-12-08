<?php
/**
 * Este script têm por objetivo buscar a listagem de arquivos para o usuário 
 * através da entidade DesafioWiser\Api\Api.
 * 
 * Script criado de forma simplificada apenas para alimentação do front-end do desafio.
 * 
 * @author Vinícius Lima <vinicius.c.lima03@gmail.com>
 */

ob_start();

require '../vendor/autoload.php';

use DesafioWiser\Api\Api;

$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
$api = new Api($_ENV['BOX_CLIENT_ID'], $_ENV['BOX_CLIENT_SECRET'], $_ENV['BOX_SUBJECT_ID']);
$api->authenticate();
$directories = $api->getFolders();
$files       = $api->getFiles();

$retorno = [];

foreach ($directories->folders as $directory) {
    array_push($retorno, $directory);
}

foreach ($files->files as $file) {
    array_push($retorno, $file);
}

ob_end_clean();
header('Content-type: application/json');
echo json_encode($retorno);
exit();