<?php

namespace Core\Repository;

use Core\model\Abstractmodel;

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
     * @param Abstractmodel $model
     */
    public function __construct(private Abstractmodel $model)
    {
    }
     
    /**
     * Requête pour récupèrer toutes les lignes d'une table
     *
     * @param array<string>|NULL $orderBy
     * @return array
     */
    public function findAll(array $orderBy = NULL):array{
        return $this->model->createQueryBuilder(NULL,$orderBy)->fetchAll();
    }

    /**
     * Requête pour récupèrer une ligne d'une table via son id
     *
     * @param integer $id
     * @return object|FALSE
     */
    public function find(int $id):object|FALSE{
        return $this->model->createQueryBuilder(["id" => $id])->fetch();
    }

    /**
     * Récupère une ligne par rapport aux critères précisés
     * 
     * @param array<string>:<string> $criteria
     * @param array<string>:<string>|NULL $orderBy
     * 
     * @return object|FALSE
    */ 
    public function findOneBy(array $criteria,array $orderBy = NULL):object|FALSE{
        return $this->model->createQueryBuilder($criteria,$orderBy)->fetch();
    }

    /**
     * Récupère toutes lignes par rapport aux critères précisés
     *
     * @param array<string>:<string> $criteria
     * @param array<string>:<string>|NULL $orderBy
     * @return object[]
     */ 
    public function findBy(array $criteria,array $orderBy = NULL):array{
        return $this->model->createQueryBuilder($criteria,$orderBy)->fetchAll();
    }

}