<?php
/**
 * Interface base paraa autenticação de usuários no sistema.
 * 
 * Todo authenticator deve implementar esta interface.
 * 
 * @package DesafioWiser
 * @subpackage Authenticator
 * @author Vinícius Lima <vinicius.c.lima03@gmail.com>
 */

namespace DesafioWiser\Authenticator;

interface IAuthenticator
{
    /**
     * Realiza a autenticação de um usuário genérico no sistema.
     * 
     * @param \DesafioWiser\User\User $user
     * @return bool
     */
    public function authenticate(\DesafioWiser\User\User $user) : bool;
}