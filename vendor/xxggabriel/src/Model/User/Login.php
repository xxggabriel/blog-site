<?php 

namespace App\Model\User;

use App\Model\Sql;

class Login extends User
{

    const NAME_SESSION = "user_session";

    public function login($username, $password, $cookie = false)
    {
        $sql = new Sql;
        
        $user = $sql->select("SELECT username FROM User WHERE username = :username", [
            ":username" => $username
        ]);

        if(empty($user)){
            throw new \Exception("Usuário não encontrado.");
        }
        $idUser = $this->getUserIdByUsername($username);
        $userPasswod = @$sql->select("SELECT password, status FROM UserPassword WHERE idUser = :idUser ORDER BY created DESC", [
            ":idUser" => $idUser
        ])[0];

        if(!password_verify($password, $userPasswod["password"]) && $userPasswod["status"] != 0){
            throw new \Exception("Senha incorreta.");
        }

        $this->createSession($idUser, $cookie);

    }

    private function createSession($idUser, $cookie)
    {
        $hash = sha1(uniqid());
        
        $_SESSION["user_id"] = $idUser;
        $_SESSION[Login::NAME_SESSION] = $hash;
        $_SESSION["logged"] = true;

        if($cookie){
            setcookie(Login::NAME_SESSION, $hash, 60*60*21*30);
            setcookie("user_id", $idUser, 60*60*21*30);
        }

        $this->sql->query("INSERT INTO Login (idUser, hash, ip, status) VALUES (:idUser, :hash, :ip, :status)", [
            ":idUser" => $idUser,
            ":hash" => $hash,
            ":ip" => $_SERVER['REMOTE_ADDR'],
            ":status" => 1 // Logado
        ]);
    }

    public function verifyLogin()
    {
        // if(empty($_COOKIE[Login::NAME_SESSION]) || empty($_SESSION[Login::NAME_SESSION])){
        //     throw new \Exception("Aréa restrita a usuários logados.");
        // }
        return true;
    }

    

}
