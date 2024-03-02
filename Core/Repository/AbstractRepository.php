<?php

namespace Core\Repository;

use Core\Model\AbstractModel;

/**
 * Permet d'obtenir les 4 requêtes par défaut
 * * findAll(["colonne"=> {"ASC" || "DESC"} ] = null);
 * * findBy(["nom"=>"valeurs],["colonne"=> {"ASC" || "DESC"} ] = null);
 * * find(id);
 * * findOneBy(["nom"=>"valeurs],["colonne"=> {"ASC" || "DESC"} ] = null);
 */
abstract class AbstractRepository{
    
    /**
     * Le model lié au Repository
     * 
     * @param AbstractModel $model
     */
    public function __construct(private AbstractModel $model)
    {
    }
     
    /**
     * Requête pour récupèrer toutes les lignes d'une table
     *
     * @param array<string>|null $orderBy
     * @return AbstractModel[]
     */
    public function findAll(array $orderBy = null):array{
        $datas = [];
        $stmt = $this->model->select(null,$orderBy)->fetchAll();
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
        $data = $this->model->select(["id" => $id])->fetch();
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
        $data = $this->model->select($criteria,$orderBy)->fetch();
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
        $stmt = $this->model->select($criteria,$orderBy)->fetchAll();
        foreach($stmt as $data){
            $this->model->hydrate($data);
            $datas[] = $this->model;
        }
       return $datas;
    }

}