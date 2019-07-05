<?php

namespace App\Model\User;

use App\Model\Sql;
use App\Controller\Utility;

class User
{

    protected $sql;   
    private $idUser,
            $name,
            $email,
            $username,
            $password;
     

    public function __construct()
    {
        $this->sql = new Sql();
    }

    public function create(string $name,string $username,string $email,string $password)
    {
        
        $this->setName($name);
        $this->setUsername($username, true);
        $this->setEmail($email, true);

        $resultUser = $this->sql->query("CALL create_user(:name, :username, :email, :status)", [
            ":name" => $this->getName(),
            ":username" => $this->getUsername(),
            ":email" => $this->getEmail(),
            ":status" => 0 // Sem senha
        ]);

        if(!$resultUser){
            throw new \Exception("Não foi possivel criar o usuário.", 500);
            
        }
        $idUser = $this->getUserIdByUsername($username);

        $this->updatePassword($idUser, $password);
        return tru;
        
    }

    public function read($idUser = null, $limit = 10)
    {
        return $this->sql->select("CALL read_user(:idUser, :limit)",[
            ":idUser" => $idUser,
            ":limit" => $limit
        ]);
    }

    public function getUserIdByEmail($email)
    {
        $this->setEmail($email);

        $idUser = $this->sql->select("SELECT idUser FROM User WHERE email = :email", [
            ":email" => $this->getEmail()
        ])[0]["idUser"];

        if(empty($idUser)){
            throw new \Exception("Não existe nenhum usuário com esse email.", 404); 
        }

        return (int)$idUser;
    }

    public function getUserIdByUsername($username)
    {
        $this->setUsername($username);

        $idUser = $this->sql->select("SELECT idUser FROM User WHERE username = :username", [
            ":username" => $this->getUsername()
        ])[0]["idUser"];

        if(empty($idUser)){
            throw new \Exception("Não existe nenhum usuário com esse nome de usuário.", 404); 
        }

        return (int)$idUser;
    }

    public function update(int $idUser, $name, $username, $email)
    {   
        $sql = new sql();

        $user = $sql->select("SELECT * FROM User WHERE idUser = :idUser", [
            ":idUser" => $idUser
        ])[0];

        $this->setIdUser($idUser, true);
        $this->setName($name);
        $this->setUsername($username, ($user["username"] == $username) ? false : true);
        $this->setEmail($email, ($user["email"] == $email) ? false : true);

        
        $resultUpdateUser = $sql->query("CALL update_user(:idUser,:name, :username, :email)", [
            ":idUser" => $this->getIdUser(),
            ":name" => $this->getName(),
            ":username" => $this->getUsername(),
            ":email" => $this->getEmail()
        ]);

        if(!$resultUpdateUser)
            throw new \Exception("Erro ao atualizar o usuário.", 500);
            
        return true;
    }

    public function updatePassword($idUser, $password)
    {

        $this->setIdUser($idUser, true);
        $this->setPassword($password);
        
        $resultUpdateUser = $this->sql->query("CALL create_password(:idUser, :password, :ip, :status)", [
            ":idUser" => $this->getIdUser(),
            ":password" => $this->getPassword(),
            ":ip" => $_SERVER['REMOTE_ADDR'],
            ":status" => 1 // Verificado e funcionando como padrão.
        ]);

        if(!$resultUpdateUser)
            throw new \Exception("Erro ao atualizar a senha.", 500);
        
        return true;
            
        
    }

    public function getIdUser()
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser, $checkExists = false)
    {
        if(empty($idUser))
            throw new \Exception("ID so usuário não informado.", 404);
            
        if($checkExists){
            
            $user = $this->read($idUser, 1)[0];

            if(empty($user)){
                throw new \Exception("Não existe nenhum usuário com esse ID.", 404);
            }
        }
        
        $this->idUser = $idUser;
            
    }

    public function getName()
    {
        return $this->name;
    }

    
    public function setName($name)
    {
        if(empty($name))
            throw new \Exception("Nome não informado.", 404);
        
        if(strlen($name) > 50)
            throw new \Exception("Nome grande de mais, tente abreviar.", 403);
        
        $this->name = strtolower($name);
    }

   
    public function getEmail()
    {
        return $this->email;
    }

     
    public function setEmail($email, $checkExists = false)
    {
        if(empty($email))
            throw new \Exception("Email não informado.", 403);

        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            throw new \Exception("Esse email não é válido.", 403);
            
        if(strlen($email) > 60)
            throw new \Exception("Email muinto grande, tente usar um menor que 60 caracteres.", 403);

        if($checkExists){
            $emailExist = $this->sql->select('SELECT email FROM User WHERE email = :email', [":email" => $email]);
            if($emailExist)
                throw new \Exception("Email já existe, tente utilizar outro.", 406);
        }
        
        $this->email = strtolower($email);
    }

   
    public function getUsername()
    {
        return $this->username;
    }

     
    public function setUsername($username, $checkExists = false)
    {
        if(empty($username))
            throw new \Exception("Nome de usuário não informado.", 403);

        if(strlen($username) > 10)
            throw new \Exception("Nome de usuário muinto grande, tente usar um menor que 10 caracteres.", 403);
        
        if($checkExists){
            $usernameExist = $this->sql->select('SELECT username FROM User WHERE username = :username', [":username" => $username]);
            if($usernameExist)
                throw new \Exception("Nome de usuário já existe, tente utilizar outro.", 406);
        }
        $this->username = strtolower($username);

    }

   
    public function getPassword()
    {
        return $this->password;
    }

     
    public function setPassword($password)
    {
        if(empty($password))
            throw new \Exception("Senha não informada.", 403);
        
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

}
