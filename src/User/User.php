<?php
/**
 * Classe base do usário do sistema de gerenciamento de Arquivos.
 * Todo usuário deverá extender ou ser instância desta entidade.
 * 
 * @package DesafioWiser
 * @subpackage User
 * @author Vinícius Lima <vinicius.c.lima03@gmail.com>
 */

namespace DesafioWiser\User;

use IUser as UserInterface;
use \DesafioWiser\Authenticator\AuthenticatorFactory as Auth;

class User implements IUser
{
    /** 
     * @name login
     * @access private
     * @var string
     */
    private $login;

    /** 
     * @name password
     * @access private
     * @var string
     */
    private $password;

    /**
     * Indica se usuário está logado no sistema.
     * @name logged
     * @access private
     * @var bool
     */
    private $logged = false;

    /**
     * @see IUser::login()
     */
    public function login(?string $login = null, ?string $password = null) : void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable( dirname(dirname(__DIR__)));
        $dotenv->load();

        $this->login = $login ?? $this->login;
        $this->password = $password ?? $this->password;
        $authenticator = $_ENV["USER_AUTHENTICATOR_DEFAULT"] ?? null;

        if ( is_null($this->login) || is_null($this->password) ) {
            throw new \Exception("Login e/ou senha não informado(s)!");
        } elseif ( empty($authenticator) ) {
            throw new \Exception("Método de autenticação padrão não definido!");
        }

        $auth = Auth::getInstance($authenticator);

        $this->logged = false;

        if (true === $auth->authenticate($this)) {
            $this->logged = true;
        }
    }

    /**
     * Retorna se o usuário está logado.
     * 
     * @return bool
     */
    public function isLogged() : bool
    {
        return $this->logged;
    }

    /**
     * Cadastra um novo usuário no sistema.
     * @see IUser::register()
     */
    public function register(?string $login = null, ?string $password = null) : \DesafioWiser\User\User
    {
        $retorno = false;

        $dotenv = \Dotenv\Dotenv::createImmutable( dirname(dirname(__DIR__)));
        $dotenv->load();

        $this->login = $login ?? $this->login;
        $this->password = $password ?? $this->password;
        $authenticator = $_ENV["USER_AUTHENTICATOR_DEFAULT"] ?? null;

        if ( is_null($this->login) || is_null($this->password) ) {
            throw new \Exception("Login e/ou senha não informado(s)!");
        } elseif ( empty($authenticator) ) {
            throw new \Exception("Método de autenticação padrão não definido!");
        }

        $auth = Auth::getInstance($authenticator);

        $this->logged = false;

        if (true === $auth->register($this)) {
            $this->logged = true;
        }

        return $this;
    }

    /** SETTERS e GETTERS */

    /**
     * Seta o login do usuário.
     * 
     * @param string $login
     * @return void
     */
    public function setLogin(string $login) : void
    {
        $this->login = $login;
    }

    /**
     * Seta a senha do usuário.
     * 
     * @param string $password
     * @return void
     */
    public function setPassword(string $password) : void
    {
        $this->password = $password;
    }

    /**
     * Retorna o login do usuário.
     * 
     * @return null | string
     */
    public function getLogin() : ?string
    {
        return $this->login;
    }

    /**
     * Retorna a senha do usuário.
     * 
     * @return string | null
     */
    public function getPassword() : ?string
    {
        return $this->password;
    }
}