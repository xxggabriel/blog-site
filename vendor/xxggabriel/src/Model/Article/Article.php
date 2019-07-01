<?php 

namespace App\Model\Article;

use App\Model\Sql;
use App\Controller\Utility;

class Article 
{

    protected $sql;
    private $title,
            $description,
            $image,
            $url,
            $tags,
            $category;



    public function getTitle()
    {
        return $this->title;
    }


    public function setTitle($title)
    {
        if(strlen($title) > 100){
            throw new \Exception("O titulo, não pode utrapassar 100 caracteres.", 403);
            
        }
        $this->title = empty($title) ? null : $title;

    }
 
    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        if(strlen($description) > 500){
            throw new \Exception("A descrição, não pode utrapassar 500 caracteres.", 403);
            
        }
        $this->description = empty($description) ? null : $description;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
                                        // Image Not Found
        $this->image = empty($image) ? "https://live.staticflickr.com/65535/48116665608_a0eb537444_b.jpg" : Utility::decreaseImageQuality($image["tmp_name"], 85);
        
    }


    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        if(strlen($url) > 100){
            throw new \Exception("A url, não pode utrapassar 100 caracteres.", 403);
            
        }

        $this->url = empty($url) ? null : $url;

    }

    public function getTags()
    {
        return $this->tags;
    }

  
    public function setTags($tags)
    {
        if(strlen($tags) > 100){
            throw new \Exception("As tags, não pode utrapassar 500 caracteres.", 403);
            
        }
        $this->tags = empty($tags) ? null : $tags;
    }


    public function getIdCategory()
    {
        return $this->category;
    }
    public function setIdCategory(int $category)
    {
        $this->category = $category;

    }

    public function __construct()
    {
        $this->sql = new Sql();
    }

    public function create(int $idUser, $title, $description, $body, $image, $url, $tags, int $idCategory)
    {

        $this->setTitle($title);
        $this->setDescription($description);
        $this->setImage($image);
        $this->setUrl($url);
        $this->setTags($tags);
        $this->setIdCategory($idCategory);

        
        return $this->sql->query("CALL create_article(:idUser, :title, :description, :body, :image, :url, :tags, :idCategory)", [
            ":idUser" => $idUser,
            ":title" => $this->getTitle(),
            ":description" => $this->getDescription(),
            ":body" => $body,
            ":image" => $this->getImage(),
            ":url" => $this->getUrl(),
            ":tags" => $this->getTags(),
            ":idCategory" => $this->getIdCategory()
        ]);
        
    }

    public function read($url = null, $limit = 10)
    {
        return $this->sql->select("CALL read_article(:url, :limit)", [
            ":url" => $url,
            ":limit" => $limit
        ]);
    }

    public function showShortArticle(int $limit = 10)
    {
        return $this->sql->select("CALL show_short_article(:limit)", [
            ":limit" => $limit
        ]);
    }

    public function popularPosts($limit = 10)
    {
        return $this->sql->select("CALL popular_posts(:limit)",[
            ":limit" => $limit
        ]);
    }

    public function readCategory(int $limit = 5)
    {
        return $this->sql->select("CALL read_category_article(:limit)",[
            ":limit" => $limit
        ]);
    }

    public function update(int $idArticle, array $data)
    {
        foreach ($data as $key => $value) {
            $this->sql->query("UPDATE Article SET $key = :value WHERE idArticle = :idArticle", [
                ":value" => $value,
                ":idArticle" => $idArticle
            ]);
        }
    }

    public function delete($idArticle)
    {
        $this->sql->query("DELETE FROM Article WHERE idArticle = :idArticle", [
            ":idArticle" => $idArticle
        ]);
    }

    
    
}