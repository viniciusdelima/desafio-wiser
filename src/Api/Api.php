<?php
/**
 * Entidade responsável por representar e fazer o intermédio entre 
 * a Api do box.com e o sistema.
 * 
 * @package DesafioWiser
 * @subpackage Api
 * @author Vinícius Lima <vinicius.c.lima03@gmail.com>
 */

namespace DesafioWiser\Api;

use GuzzleHttp\Client as Client;

use DesafioWiser\Api\ApiParse;

class Api
{
    /**
     * @name Indica se a api está autenticada e com um token válido.
     * @access private
     * @var bool
     */
    private $authenticated = false;

    /**
     * Url base da api.
     * @name urlBase
     * @access private
     * @var string
     */
    private $urlBase = 'https://api.box.com';

    /**
     * Versão da Api.
     * @name version
     * @access private
     * @var string
     */
    private $version = '2.0';

    /**
     * Client Http.
     * @name client
     * @access private
     * @var GuzzleHttp\Client
     */
    private $client;

    /**
     * ACCESS TOKEN para as requisições correntes.
     * @name accessToken
     * @access private
     * @var string
     */
    private $accessToken;

    /**
     * Timestamp em que o access token expirará.
     * @name tokenExpirationTime
     * @access private
     * @var int
     */
    private $tokenExpirationTime = 0;

    /**
     * CLIENT_ID utilizado na integração com a Api.
     * @name client_id
     * @access private
     * @var string
     */
    private $client_id;

    /**
     * CLIENT_ID utilizado na integração com a Api.
     * @name client_id
     * @access private
     * @var string
     */
    private $client_secret;

    /**
     * SUBJECT_ID utilizado na integração com a Api.
     * @name subjectId
     * @access private
     * @var int
     */
    private $subjectId;

    /**
     * Inicia a classe e seta o client http.
     * 
     * @param string $client_id
     * @param string $client_secret
     * @param int $subject_id
     * @return void
     */
    public function __construct(string $client_id, string $client_secret, int $subject_id)
    {
        $this->client = new Client([
            'base_uri' => $this->urlBase,
            'timeout'  => 2.0,
            'verify'   => false
        ]);

        $this->client_id     = $client_id;
        $this->client_secret = $client_secret;
        $this->subjectId     = $subject_id;
    }

    /**
     * Autentica a aplicação na Api através do fluxo client credentials com oAuth2.
     * 
     * @return void
     * @throws \Exception
     */
    public function authenticate() : void
    {
        $response = $this->client->post('/oauth2/token', [
            'form_params' => [
                'grant_type'       => 'client_credentials',
                'client_id'        => $this->client_id,
                'client_secret'    => $this->client_secret,
                'box_subject_type' => 'user',
                'box_subject_id'   => $this->subjectId
            ]
        ]);

        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody());
            $this->accessToken = $data->access_token;
            $this->tokenExpirationTime = strtotime('+' . $data->expires_in . ' second');
            
            $this->authenticated = true;
        }
    }

    /**
     * Retorna se a Api está autenticada e com um token válido.
     * 
     * @return bool
     */
    public function isAuthenticated() : bool
    {
        if (empty($this->tokenExpirationTime) || empty($this->accessToken)) {
            $this->authenticated = false;
            $this->tokenExpirationTime = null;
            $this->accessToken = null;
        } elseif (!empty($this->tokenExpirationTime) && $this->tokenExpirationTime <= time() ) {
            $this->authenticated = false;
            $this->tokenExpirationTime = null;
            $this->accessToken = null;
        }

        return $this->authenticated;
    }

    /**
     * Retorna um array contendo os arquivos do usuários no box.com 
     * para um diretório pai informado.
     * Caso não seja informado um diretório pai, a raiz será considerada.
     * 
     * @param int | null $directory
     * @return \stdClass
     */
    public function getFiles(int $directory = 0) : \stdClass
    {
        $retorno = [];

        if (!$this->isAuthenticated()) {
            $this->authenticate();
        }

        $endpoint = '/' . $this->version . '/folders/' . $directory . '/items';

        $response = $this->client->get($endpoint, ['headers' => 
            [
                'Authorization' => "Bearer {$this->accessToken}"
            ]
        ]);

        if ($response->getStatusCode() === 200) {
            $data = \json_decode($response->getBody());

            $files   = ApiParse::parseFiles($data);
            $retorno = $files;
        }

        return $retorno;
    }

    /**
     * Retorna os diretórios filhos de um diretório no box.com.
     * Caso um diretório pai não seja informado, será utilizado o diretório raiz.
     * 
     * @param int | $parent
     * @return \stdClass
     */
    public function getFolders(int $parent = 0) : \stdClass
    {
        $retorno = [];

        if (!$this->isAuthenticated()) {
            $this->authenticate();
        }

        $endpoint = '/' . $this->version . '/folders/' . $parent;

        $response = $this->client->get($endpoint, ['headers' => 
            [
                'Authorization' => "Bearer {$this->accessToken}"
            ]
        ]);

        if ($response->getStatusCode() === 200) {
            $data    = \json_decode($response->getBody());
            $folders = ApiParse::parseFolders($data);
            $retorno = $folders;
        }

        return $retorno;
    }

    /**
     * Deleta um arquivo na api do box.com.
     * 
     * @param int $id
     * @return boolean
     */
    public function deleteFile(int $id) : bool
    {
        $retorno = false;

        if (!$this->isAuthenticated()) {
            $this->authenticate();
        }

        $endpoint = '/' . $this->version . '/files/' . $id;

        $response = $this->client->delete($endpoint, ['headers' => 
            [
                'Authorization' => "Bearer {$this->accessToken}"
            ]
        ]);

        if ($response->getStatusCode() === 204) {
            $retorno = true;
        }

        return $retorno;
    }

    /**
     * Deleta um diretório na api do box.com.
     * 
     * @param int $id
     * @return boolean
     */
    public function deleteFolder(int $id) : bool
    {
        $retorno = false;

        if (!$this->isAuthenticated()) {
            $this->authenticate();
        }

        // Limpa o diretório antes de apagá-lo
        $files = $this->getFiles($id);

        foreach ($files->files as $file) {
            $this->deleteFile($file->id);
        }

        $endpoint = '/' . $this->version . '/folders/' . $id;

        $response = $this->client->delete($endpoint, ['headers' => 
            [
                'Authorization' => "Bearer {$this->accessToken}"
            ]
        ]);

        if ($response->getStatusCode() === 204) {
            $retorno = true;
        }

        return $retorno;
    }
}