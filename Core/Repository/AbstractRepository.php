<?php

namespace Core\Repository;

use Core\Model\AbstractModel;
use ReflectionMethod;

/**
 * Permet d'obtenir les 4 requêtes par défaut
 * * findAll(["colonne"=> {"ASC" || "DESC"} ] = null);
 * * findBy(["nom"=>"valeurs],["colonne"=> {"ASC" || "DESC"} ] = null);
 * * find(id);
 * * findOneBy(["nom"=>"valeurs],["colonne"=> {"ASC" || "DESC"} ] = null);
 */
abstract class AbstractRepository{
    
    /**
     * Permet de modifier la visibilité de la methode select d'AbstractModel.
     *
     * @var ReflectionMethod $select
     */
    protected ReflectionMethod $select;

    /**
     * Le model lié au Repository
     * 
     * Pour créer une nouvelle methode utilisez le code php ci-dessous pour lire dans la base de données.
     * 
     * $this->select->invoke($this->model,...args)->fetch()|fetchAll()
     * 
     * @param AbstractModel $model
     * @var ReflectionMethod $select
     */
    public function __construct(protected AbstractModel $model)
    {
        //Récupère la méthode select d'AbstractModel, ! le string du nom de la classe intérfère avec l'Autoloader.
        $this->select = new ReflectionMethod(AbstractModel::class, "select");
    }
     
    /**
     * Requête pour récupèrer toutes les lignes d'une table
     *
     * @param array<string>|null $orderBy
     * @return AbstractModel[]
     */
    public function findAll(array $orderBy = null):array{
        $datas = [];
        // Change l'accessibilité de la méthode pour l'execusion de la commande private => public
        $this->select->setAccessible(true);
        $stmt = $this->select->invoke($this->model,null,$orderBy)->fetchAll();
        $this->select->setAccessible(false);
        foreach($stmt as $data){
            $this->model->hydrate($data);
            $datas[] = $this->model;
        }
       return $datas;
    }

    /**
     * Requête pour récupèrer une ligne d'une table via son id
     * 
     * @param int $id
     * 
     * @return AbstractModel|null
     */
    public function find(int $id):?AbstractModel{
        // Change l'accessibilité de la méthode pour l'execusion de la commande private => public
        $this->select->setAccessible(true);
        $data = $this->select->invoke($this->model,["id" => $id])->fetch();
        $this->select->setAccessible(false);
        return $data != false ? $this->model->hydrate($data) : null;
    }

    /**
     * Récupère une ligne par rapport aux critères précisés
     * 
     * @param array<string>:<string> $criteria
     * @param array<string>:<string>|null $orderBy
     * 
     * @return AbstractModel|null
     * 
    */ 
    public function findOneBy(array $criteria,array $orderBy = null):?AbstractModel{
        // Change l'accessibilité de la méthode pour l'execusion de la commande private => public
        $this->select->setAccessible(true);
        $data = $this->select->invoke($this->model,$criteria,$orderBy)->fetch();
        $this->select->setAccessible(false);
        return $data != false ? $this->model->hydrate($data) : null;
    }

    /**
     * Récupère toutes lignes par rapport aux critères précisés
     *
     * @param array<string>:<string> $criteria
     * @param array<string>:<string>|null $orderBy
     * @return AbstractModel[]
     */ 
    public function findBy(array $criteria,array $orderBy = null):array{
        $datas = [];
        // Change l'accessibilité de la méthode pour l'execusion de la commande private => public
        $this->select->setAccessible(true);
        $stmt = $this->select->invoke($this->model,$criteria,$orderBy)->fetchAll();
        $this->select->setAccessible(false);
        foreach($stmt as $data){
            $this->model->hydrate($data);
            $datas[] = $this->model;
        }
       return $datas;
    }

}