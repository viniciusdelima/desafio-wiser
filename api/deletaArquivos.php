<?php
/**
 * Este script têm por objetivo realizar a remoção de arquivos para o usuário 
 * através da entidade DesafioWiser\Api\Api.
 * 
 * Script criado de forma simplificada apenas para alimentação do front-end do desafio.
 * 
 * @author Vinícius Lima <vinicius.c.lima03@gmail.com>
 */

ob_start();

require '../vendor/autoload.php';

use DesafioWiser\Api\Api;

$type = trim($_POST['type']) ?? null;
$id = trim($_POST['id']) ?? null;

$code = 400;

if (!empty($type) && !empty($id)) {
    $dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
    $api = new Api($_ENV['BOX_CLIENT_ID'], $_ENV['BOX_CLIENT_SECRET'], $_ENV['BOX_SUBJECT_ID']);
    $api->authenticate();
    
    if ($type === 'file') {
        $api->deleteFile($id) === true ? $code = 204 : $code;
    } else if ($type === 'folder') {
        $api->deleteFolder($id) === true ? $code = 204 : $code;
    }
}

ob_end_clean();
http_response_code($code);
exit();