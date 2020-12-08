<?php
/**
 * Interface base do usário do sistema de gerenciamento de Arquivos.
 * 
 * @package DesafioWiser
 * @subpackage User
 * @author Vinícius Lima <vinicius.c.lima03@gmail.com>
 */

namespace DesafioWiser\User;

interface IUser
{
    /**
     * Realiza o login de um usuário genérico no sistema.
     * 
     * @param string | null $login
     * @param string | null $password
     * @return void
     */
    public function login(?string $login = null, ?string $password = null) : void;

    /**
     * Cadastra um novo usuário no sistema.
     * 
     * @param string | null $login
     * @param string | null $password
     * @return \DesafioWiser\User\User
     */
    public function register(?string $login = null, ?string $password = null) : \DesafioWiser\User\User;
}