<?php 

namespace App\Model\Article;

use App\Model\Sql;
use App\Controller\Utility;

class Article 
{

    protected $sql;
    private $idArticle,
            $title,
            $description,
            $image,
            $url,
            $tags,
            $idCategory,
            $nameCategory;


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

    public function createCategory(string $name)
    {
        $this->setNameCategory($name, true);
        $savedCategory = $this->sql->query("CALL create_category_article(:name)", [
            ":name" => $this->getNameCategory()
        ]);

        if(!$savedCategory)
            throw new \Exception("Não foi possivel salvar a categoria", 1);
        
        return true;
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


    public function getIdArticle()
    {
        return $this->idArticle;
    }

    public function setIdArticle(int $idArticle, bool $checkIdArticle = false)
    {
        if(empty($idArticle))
            throw new \Exception("ID do artigo, não informado.", 404);
        
        if($checkIdArticle){
            $resultIdUser = $this->sql->select("SELECT idArticle FROM Article WHERE idArticle = :idArticle", [
                ":idArticle" => $idArticle
            ])[0]["idArticle"];

            if(!$resultIdUser)
                throw new \Exception("ID do artigo, não encontrado.", 404);
                
        }
        $this->idArticle = $idArticle;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }


    public function setTitle($title)
    {
        if(empty($title))
            throw new \Exception("Titulo do artigo, não informado", 404);
            
        if(strlen($title) > 100){
            throw new \Exception("O titulo, não pode utrapassar 100 caracteres.", 403);
            
        }
        $this->title = $title;

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
        if(empty($url))
            throw new \Exception("URL não informada;", 404);
            
        if(strlen($url) > 100){
            throw new \Exception("A url, não pode utrapassar 100 caracteres.", 403);
            
        }

        $this->url = $url;

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
    public function setIdCategory(int $idCategory, $checkIdCategory = false)
    {

        if($checkIdCategory){
            $resultIdCategory = $this->sql->select("SELECT idCategorry FROM ArticleCategory WHERE idCategory = :idCategory", [
                ":idCategory" => $idCategory
            ])[0]["idCategory"];

            if($resultIdCategory)
                throw new \Exception("ID da categoria não existe.", 404);
                
        }

        $this->idCategory = $idCategory;

    }

             
    public function getNameCategory()
    {
        return $this->nameCategory;
    }

    
    public function setNameCategory(string $nameCategory, $checkNameCategory = false)
    {
        if($checkNameCategory){  
            $existeName = $this->sql->select('SELECT name ArticleCategory WHERE name = :name', [
                ":name" => $nameCategory
            ])[0]["name"];

            if($existeName)
                throw new \Exception("Nome da categoria já exite, tente utilizar outra.", 403);
        }
            
        $this->nameCategory = $nameCategory;

    }
}