<?php

namespace App\Models;

use ArrayObject;
use DateTimeImmutable;
use Core\Model\AbstractModel;

class User extends AbstractModel{

    protected int $id;
    protected DateTimeImmutable $created_at;
	protected string $nom;
    protected ArrayObject $articles;

    public function __construct() {
        $this->table = "User";
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

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return Article[]
     */
    public function getArticles(): ArrayObject {
        return $this->articles;
    }

    /**
     * Undocumented function
     *
     * @param Article $article
     * @return self
     */
    public function addArticle(Article $article): self {
        $this->articles->append($article);
        if($article->getUser() == null){
            $article->setUser($this);
        }
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param Article[] $articles
     * @return self
     */
    public function addArticles(array $articles): self {
        foreach($articles as $article){
            $this->articles->append($article);
            if($article->getUser() == null){
                $article->setUser($this);
            }
        }
        return $this;
    }
}