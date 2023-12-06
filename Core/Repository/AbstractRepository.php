<?php

namespace Core\Repository;

use Core\Model\AbstractModel;

/**
 * Permet d'obtenir les 4 requêtes par défaut
 * * findAll(["colonne"=> {"ASC" || "DESC"} ] = NULL);
 * * findBy(["nom"=>"valeurs],["colonne"=> {"ASC" || "DESC"} ] = NULL);
 * * find(id);
 * * findOneBy(["nom"=>"valeurs],["colonne"=> {"ASC" || "DESC"} ] = NULL);
 */
abstract class AbstractRepository{

    /**
     * Le model lié au Repository
     *
     * @param $model
     */
    public function __construct(private AbstractModel $model)
    {
    }
     
    /**
     * Requête pour récupèrer toutes les lignes d'une table
     *
     * @param array<string>|NULL $orderBy
     * @return object[]
     */
    public function findAll(array $orderBy = NULL):array{
        $datas = [];
        $stmt = $this->model->select(NULL,$orderBy)->fetchAll();
        foreach($stmt as $data){
            $model = new $this->model();
            $model->hydrate($data);
            $datas[] = $model;
        }
       return $datas;
    }
    /**
     * Requête pour récupèrer une ligne d'une table via son id
     */
    public function find(int $id) {
        $data = $this->model->select(["id" => $id])->fetch();
        $model = new $this->model();
        $model->hydrate($data);
        return $model;
    }

    /**
     * Récupère une ligne par rapport aux critères précisés
     * 
     * @param array<string>:<string> $criteria
     * @param array<string>:<string>|NULL $orderBy
     * 
    */ 
    public function findOneBy(array $criteria,array $orderBy = NULL){
        $data = $this->model->select($criteria,$orderBy)->fetch();
        $model = new $this->model();
        $model->hydrate($data);
        return $model;
    }

    /**
     * Récupère toutes lignes par rapport aux critères précisés
     *
     * @param array<string>:<string> $criteria
     * @param array<string>:<string>|NULL $orderBy
     * @return object[]
     */ 
    public function findBy(array $criteria,array $orderBy = NULL):array{
        $datas = [];
        $stmt = $this->model->select($criteria,$orderBy)->fetchAll();
        foreach($stmt as $data){
            $model = new $this->model();
            $model->hydrate($data);
            $datas[] = $model;
        }
       return $datas;
    }

}