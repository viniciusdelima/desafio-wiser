<?php
/**
 * Classe de Teste para entidade Api de integração com o box.com.
 * 
 * @package DesafioWiser
 * @subpackage Api
 * @author Vinícius Lima <vinicius.c.lima03@gmail.com>
 */

namespace DesafioWiser\Test\Api;

use PHPUnit\Framework\TestCase;

use DesafioWiser\Api\Api as Api;

class ApiTest extends TestCase
{
    /**
     * Prepara o ambiente de teste.
     */
    protected function setUp() : void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable( dirname(dirname(__DIR__)));
        $dotenv->load();
    }

    /**
     * Testa a inicialização da classe.
     */
    public function assertPreConditions() : void
    {
        $this->assertTrue( class_exists('DesafioWiser\Api\Api') );
    }

    /**
     * Testa a autenticação com a Api.
     */
    public function testAutenticaComClientCredentials() : void
    {
        $api = new Api($_ENV['BOX_CLIENT_ID'], $_ENV['BOX_CLIENT_SECRET'], $_ENV['BOX_SUBJECT_ID']);
        $api->authenticate();
        $this->assertTrue($api->isAuthenticated());
    }

    /**
     * Testa a listagem de arquivos vindos da Api.
     */
    public function testBuscaArquivosNoBox() : void
    {
        $api = new Api($_ENV['BOX_CLIENT_ID'], $_ENV['BOX_CLIENT_SECRET'], $_ENV['BOX_SUBJECT_ID']);
        $api->authenticate();
        $directories = $api->getFolders();
        $files       = $api->getFiles();
    }

    /**
     * Deleta um arquivo na api do box.com.
     */
    public function testDeletaArquivoNoBox(): void
    {
        $api = new Api($_ENV['BOX_CLIENT_ID'], $_ENV['BOX_CLIENT_SECRET'], $_ENV['BOX_SUBJECT_ID']);
        $api->authenticate();
        $files       = $api->getFiles();
        
        if ( isset($files->files[0]) ) {
            $existe = false;
            $id     = $files->files[0]->id;
            $this->assertTrue($api->deleteFile($id));
        }
    }

    /**
     * Deleta um folder na api do box.com.
     */
    public function testDeletaDiretorioNoBox(): void
    {
        $api = new Api($_ENV['BOX_CLIENT_ID'], $_ENV['BOX_CLIENT_SECRET'], $_ENV['BOX_SUBJECT_ID']);
        $api->authenticate();
        $directories = $api->getFolders();
        
        if ( isset($directories->folders[0]) ) {
            $existe = false;
            $id     = $directories->folders[0]->id;
            $this->assertTrue($api->deleteFolder($id));
        }
    }

    /**
     * Testa o upload de um arquivo para o box.com.
     */
    public function testFazUploadArquivo() : void
    {
        $api = new Api($_ENV['BOX_CLIENT_ID'], $_ENV['BOX_CLIENT_SECRET'], $_ENV['BOX_SUBJECT_ID']);
        $api->authenticate();
        $file = $_ENV['FILE_TEST_UPLOAD'];
        $fileName = $_ENV['FILE_TEST_UPLOAD_NAME'];
        $this->assertTrue($api->upload($file, $fileName));
    }
}