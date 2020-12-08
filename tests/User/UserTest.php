<?php
/**
 * Classe de Teste para entidade User.
 * 
 * @package DesafioWiser
 * @subpackage User
 * @author Vinícius Lima <vinicius.c.lima03@gmail.com>
 */

namespace DesafioWiser\Test\User;

use PHPUnit\Framework\TestCase;

use \DesafioWiser\User\User as User;

class UserTest extends TestCase
{
    /**
     * Testa a inicialização da classe.
     */
    public function assertPreConditions() : void
    {
        $this->assertTrue( class_exists('DesafioWiser\User\User') );
    }

    /**
     * Testa o cadastro de um usuário no sistema.
     */
    public function testCadastraUsuarioComSucesso() : void
    {
        $user = new User();
        $user->setLogin('teste3@teste.com');
        $user->setPassword('123456');

        $this->assertSame('teste3@teste.com', $user->getLogin());
        $this->assertTrue($user->register() instanceof User);
        $user->login();
        $this->assertTrue($user->isLogged());

        $user->setLogin('teste2@teste.com');
        $user->setPassword('12345678');
        $this->assertTrue($user->register() instanceof User);
        $user->login();
        $this->assertTrue($user->isLogged());

        $user->setLogin('teste@teste.com');
        $user->setPassword('123456');
        $this->assertTrue($user->register() instanceof User);
        $user->login();
        $this->assertTrue($user->isLogged());
    }

    /**
     * Testa o login do usuário.
     */
    public function testUsuarioLogadoComSucesso() : void
    {
        $user = new User();
        $user->setLogin('teste@teste.com');
        $user->setPassword('123456');
        $user->login();

        $this->assertSame('teste@teste.com', $user->getLogin());
        $this->assertTrue($user->isLogged());

        $user->setLogin('teste2@teste.com');
        $user->setPassword('12345678');
        $user->login();
        $this->assertTrue($user->isLogged());
    }
}