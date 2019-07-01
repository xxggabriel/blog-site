<?php

namespace App\Model\User;

use App\Model\Sql;

class User
{

    private $name,
            $email,
            $username,
            $password;
    protected $sql;
    
    public function getName()
    {
        return $this->name;
    }

    
    public function setName($name)
    {
        if(empty($name))
            throw new \Exception("Nome não informado.", 403);
        
        if(strlen($name) > 50)
            throw new \Exception("Nome grande de mais, tente abreviar.", 403);
        
        $this->name = strtolower($name);
    }

   
    public function getEmail()
    {
        return $this->email;
    }

     
    public function setEmail($email)
    {
        if(empty($email))
            throw new \Exception("Email não informado.", 403);

        if(strlen($email) > 60)
            throw new \Exception("Email muinto grande, tente usar um menor que 60 caracteres.", 403);

        $this->email = strtolower($email);
    }

   
    public function getUsername()
    {
        return $this->username;
    }

     
    public function setUsername($username)
    {
        if(empty($username))
            throw new \Exception("Nome de usuário não informado.", 403);

        if(strlen($username) > 10)
            throw new \Exception("Nome de usuário muinto grande, tente usar um menor que 10 caracteres.", 403);
        
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

    public function __construct()
    {
        $this->sql = new Sql();
    }

    public function create($name, $username, $email, $password)
    {
        $this->setName($name);
        $this->setUsername($username);
        $this->setEmail($email);

        $this->sql->query("CALL create_user(:name, :username, :email, :status)", [
            ":name" => $this->getName(),
            ":username" => $this->getUsername(),
            ":email" => $this->getEmail(),
            ":status" => 0 // Sem senha
        ]);
        
        $idUser = $this->sql->select("SELECT idUser FROM User WHERE username = :username",[
            ":username" => $username
        ])[0]["idUser"];

        return $this->updatePassword($idUser, $password);
        
    }

    public function read($idUser = null)
    {
        return $this->sql->select("CALL read_user(:idUser)",[
            ":idUser" => $idUser
        ]);
    }

    public function getUserIdByEmail($email)
    {
        $this->setEmail($email);

        return (int)$this->sql->select("SELECT idUser FROM User WHERE email = :email", [
            ":email" => $this->getEmail()
        ])[0]["idUser"];
    }

    public function getUserIdByUsername($username)
    {
        $this->setUsername($username);

        return (int)$this->sql->select("SELECT idUser FROM User WHERE username = :username", [
            ":username" => $this->getUsername()
        ])[0]["idUser"];
    }

    public function update($idUser,array $data)
    {
        foreach ($data as $key => $value) {
            return $this->sql->query("UPDATE User set $key = :value WHERE idUser = :idUser",[
                ":idUser" => $idUser,
                ":value" => $value
            ]);
        }
    }

    public function updatePassword($idUser, $password)
    {

        $this->setPassword($password);
        
        return $this->sql->query("CALL create_password(:idUser, :password, :ip, :status)", [
            ":idUser" => (int)$idUser,
            ":password" => $this->getPassword(),
            ":ip" => $_SERVER['REMOTE_ADDR'],
            ":status" => 1 // Verificado e funcionando como padrão.
        ]);
    }


}
