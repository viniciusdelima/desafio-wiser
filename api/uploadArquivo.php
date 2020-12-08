<?php
/**
 * Este script têm por objetivo o upload de um arquivo para o usuário 
 * através da entidade DesafioWiser\Api\Api.
 * 
 * Script criado de forma simplificada apenas para alimentação do front-end do desafio.
 * 
 * @author Vinícius Lima <vinicius.c.lima03@gmail.com>
 */

ob_start();

require '../vendor/autoload.php';

use DesafioWiser\Api\Api;

$code = 400;

if ( isset($_FILES['file']) ) {
    $name = $_FILES['file']['name'];
    $path = $_FILES['file']['tmp_name'];
    
    $dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
    $api = new Api($_ENV['BOX_CLIENT_ID'], $_ENV['BOX_CLIENT_SECRET'], $_ENV['BOX_SUBJECT_ID']);
    $api->authenticate();

    if ($api->upload($path, $name) === true) {
        $code = 201;
    }
}

ob_end_clean();
http_response_code($code);
exit();