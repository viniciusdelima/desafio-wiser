<?php
/**
 * Classe de autentitcação para o Desafio Wiser utilizando banco de dados SQLite.
 * 
 * @package DesafioWiser
 * @subpackage Authenticator
 * @author Vinícius Lima <vinicius.c.lima03@gmail.com>
 */

namespace DesafioWiser\Authenticator;

use \DesafioWiser\Authenticator\IAuthenticator;

class DWAuthenticator extends Authenticator implements IAuthenticator
{
    /**
     * @see IAuthenticator::authenticate
     */
    public function authenticate(\DesafioWiser\User\User $user) : bool
    {
        $retorno = false;

        try {
            $db    = new \SQLite3(dirname(dirname(__DIR__)) . '/database.db');

            $query = "SELECT COUNT(*) AS total FROM users WHERE login = :login AND password = :password";
            $stmt  = $db->prepare($query);
            $stmt->bindValue(':login', $user->getLogin());
            $stmt->bindValue(':password', $user->getPassword());

            $result = $stmt->execute();
            $result = $result->fetchArray(SQLITE3_ASSOC);

            isset($result["total"]) && $result["total"] > 0 ? $retorno = true : $retorno = false;
        } catch (\Exception $e) {
            throw $e;
            throw new \Exception("Nenhum usuário encontrado com as credenciais informadas");
        }

        return $retorno;
    }
    
    /**
     * Adicionalmente devido ao escopo do projeto e desacoplagem da classe pai, 
     * o authenticator terá também um método de cadastro de novos usuários.
     * 
     * @param \DesafioWiser\User\User
     * @return bool
     */
    public function register(\DesafioWiser\User\User $user) : bool
    {
        $retorno = false;

        try {
            $db    = new \SQLite3(dirname(dirname(__DIR__)) . '/database.db');

            $query = "INSERT INTO users (login, password) 
                VALUES (:login, :password)";
            $stmt  = $db->prepare($query);
            $stmt->bindValue(':login', $user->getLogin());
            $stmt->bindValue(':password', $user->getPassword());

            $result = $stmt->execute();
            $retorno = true;
        } catch (\Exception $e) {
            throw $e;
            throw new \Exception("Não foi possível cadastrar o usuário no banco de dados do sistema.");
        }       

        return $retorno;
    }
}