<?php 

namespace App\Model\Article;

class Comment extends Article
{

    private $idUser,
            $idArticle,
            $idComment,
            $body;

    public function createComment(int $idArticle, int $idUser, string $body)
    {
        $this->sql->query("INSERT INTO Article (idArticle, idUser, body) VALUES (:idArticle, :idUser, :body)", [
            ":idArticle" => $idArticle,
            ":idUser" => $idUser,
            ":body" => $body
        ]);
    }

    public function readComment($idArticle = null, $idUser = null, $idComment = null)
    {
        return $this->sql->select("CALL read_comment(:idArticle, :idUser, :idComment)", [
            ":idArticle" => $idArticle,
            ":idUser" => $idUser,
            ":idComment" => $idComment
        ]);
    }

    public function updateComment($idComment)
    {
        $this->sql->query("UPDATE Comment SET body = :body WHERE idComment = :idComment", [
            ":idComment" => $idComment
        ]);
    }

    public function deleteComment($idComment)
    {
        $this->sql->query("DELETE FROM Comment WHERE idComment= :idComment", [
            ":idComment" => $idComment
        ]);
    }

  
    public function getIdComment()
    {
        return $this->idComment;
    }

   
    public function setIdComment(int $idComment)
    {
        if(empty($idComment))
            throw new \Exception("ID do comentario, não informado.", 403);
            
        $this->idComment = $idComment;
    }


    public function getBody()
    {
        return $this->body;
    }


    public function setBody($body)
    {       
        $this->body = $body;
    }
}