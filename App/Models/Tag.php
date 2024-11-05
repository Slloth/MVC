<?php

namespace App\Models;

use ArrayObject;
use DateTimeImmutable;
use Core\Model\AbstractModel;

class Tag extends AbstractModel{

    protected int $id;
    protected DateTimeImmutable $created_at;
	protected string $libelle;
    protected ArrayObject $articles;

    public function __construct() {
        $this->table = "Tag";
        $this->articles = new ArrayObject();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getCreated_at(): DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreated_at(string $created_at): self
    {
        $this->created_at = new DateTimeImmutable($created_at);
        return $this;
    }

    public function getLibelle(): string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * Undocumented function
     */
    public function getArticles(): ArrayObject {
        return $this->articles;
    }

    public function addArticle(Article $article): self{
        $this->articles->append($article);
        return $this;
    }
    
    public function addArticles(ArrayObject $articles): self{
        foreach($articles as $article){
            $this->articles->append($article);
        }
        return $this;
    }

}