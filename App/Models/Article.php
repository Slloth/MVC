<?php

namespace App\Models;

use App\Models\User;
use ArrayObject;
use DateTimeImmutable;
use Core\Model\AbstractModel;

class Article extends AbstractModel{

    protected int $id;
    protected DateTimeImmutable $created_at;
	protected string $nom;
	protected User $user;
    protected string $description;
    protected ArrayObject $tags;

    public function __construct() {
        $this->table = "Article";
        $this->tags = new ArrayObject();
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

    public function getUser(): User|null
    {
        if(isset($this->user)){
            return $this->user;
        }
        return null;
    }
    
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }
    /**
     * Get the value of description
     */ 
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return Tag[]
     */
    public function getTags(): ArrayObject{
        return $this->tags;
    }

    public function addTag(Tag $tag): self{
        $this->tags->append($tag);
        if($tag->getArticles()->count() == 0){
            $tag->addArticle($this);
        }
        return $this;
    }

    public function addTags(ArrayObject $tags): self{
        foreach($tags as $tag){
            $this->tags->append($tag);
        }
        return $this;
    }

}