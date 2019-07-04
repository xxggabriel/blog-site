<?php 

namespace App\Model;

class Sql
{
    private $conn;
    private $id;

    const DB_NAME = "blog";
    const DB_HOST = "localhost";
    const DB_USER = "xxggabriel";
    const DB_PASSWORD = "localhost";


    public function __construct()
    {
        try {
            $this->conn = new \PDO("mysql:host=".Sql::DB_HOST.
                                    ";dbname=".Sql::DB_NAME.";",
                                    Sql::DB_USER, Sql::DB_PASSWORD,
                                    array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        } catch (\PDOException $e) {
            throw new \Exception(   "\nError: ".$e->getMessage()."\n".
                                    "Code: ".$e->getCode()."\n".
                                    "File: ".$e->getFile()."\n".
                                    "Line: ".$e->getLine());
            
        }
        
    }

    public function lastId()
    {
        return $this->id;
    }

    private function setParams($stmt, $data)
    {
        foreach ($data as $key => $value) {
            $this->bindParam($stmt, $key, $value);
        }
    }

    private function bindParam($stmt, $key, $value)
    {
        $stmt->bindParam($key, $value);
    }

    public function select($rawQuery, array $data = [])
    {
        $stmt = $this->conn->prepare($rawQuery);
        $this->setParams($stmt, $data);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function query($rawQuery, array $data = [])
    {
        $stmt = $this->conn->prepare($rawQuery);
        $this->setParams($stmt, $data);
        return $stmt->execute();
    }



    
}