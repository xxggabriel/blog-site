<?php 

namespace App\Model\User;

use App\Model\Sql;

class Api
{

    protected $sql;
    private $idUser,
            $userId,
            $token;

    public function getIdUser()
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser)
    {
        if(empty($idUser))
            throw new \Exception("ID do usuário, não informado.", 403);
            
        $this->idUser = $idUser;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        if(empty($userId))
            throw new \Exception("ID do usuário, não informado.", 403);
            
        $this->userId = $userId;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        if(empty($token))
            throw new \Exception("Token, não informado.", 403);
            
        $this->token = $token;
    }

    public function __construct()
    {
        $this->sql = new Sql();
    }

    public function createToken($idUser)
    {
        $this->setIdUser($idUser);
        $this->setUserId(sha1(uniqid()));
        $this->setToken(sha1(uniqid()));

        $this->sql->query("INSERT INTO Api (idUser, userId, token) VALUES (:idUser, :userId, :token)", [
            ":idUser" => $this->getIdUser(),
            ":userId" => $this->getUserId(),
            ":token" => $this->getToken()
        ]);
    }

    

    public function verifyTokenApi($userId, $token)
    {
        $this->setUserId($userId);
        $this->setToken($token);

        return !empty($this->sql->select("SELECT * FROM Api WHERE userId = :userId AND token = :token", [
            ":userId" => $this->getUserId(),
            ":token" => $this->getToken()
        ])[0]);
    }

}