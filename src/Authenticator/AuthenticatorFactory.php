<?php
/**
 * Classe factory para autentitcação no sistema.
 * Esta classe retornará uma instância do método de autenticação em uso.
 * 
 * @package DesafioWiser
 * @subpackage Authenticator
 * @author Vinícius Lima <vinicius.c.lima03@gmail.com>
 */

namespace DesafioWiser\Authenticator;

class AuthenticatorFactory
{
    /**
     * Retorna uma instância de uma entidade de autenticação.
     * 
     * @param string $authenticator
     * @return Authenticator
     */

    public static function getInstance(string $authenticator)
    {
        return new $authenticator();
    }
}