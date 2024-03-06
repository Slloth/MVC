<?php

namespace Core\Repository;

use Core\Model\AbstractModel;
use Core\Model\interface\ModelInterface;
use Exception;
use PDOStatement;
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
     * @var ReflectionMethod $queryBuilder
     */
    private ReflectionMethod $queryBuilder;

    /**
     * Le model lié au Repository
     * 
     * @param AbstractModel $model
     * @var ReflectionMethod $select
     */
    public function __construct(protected AbstractModel $model)
    {
        //Récupère la méthode select d'AbstractModel, ! le string du nom de la classe intérfère avec l'Autoloader.
        $this->queryBuilder = new ReflectionMethod(AbstractModel::class, "select");
    }
     
    /**
     * Requête pour récupèrer toutes les lignes d'une table
     *
     * @param array{string:string}|null $orderBy
     * @param positive-int|null $limit
     * 
     * @return AbstractModel[]
     */
    public function findAll(?array $orderBy = null, ?int $limit = null):array{
        // Change l'accessibilité de la méthode pour l'execusion de la commande private => public
        $this->queryBuilder->setAccessible(true);
        $stmt = $this->queryBuilder->invoke($this->model,null,$orderBy,$limit);
        $this->queryBuilder->setAccessible(false);
        return $this->getResult($stmt);
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
        $this->queryBuilder->setAccessible(true);
        $stmt = $this->queryBuilder->invoke($this->model,["id" =>$id]);
        $this->queryBuilder->setAccessible(false);
        return $this->getSingleResult($stmt);
    }

    /**
     * Récupère une ligne par rapport aux critères précisés
     * 
     * @param array $criteria
     * 
     * @return AbstractModel|null
     * 
    */ 
    public function findOneBy(string $criteria):?AbstractModel{
        // Change l'accessibilité de la méthode pour l'execusion de la commande private => public
        $this->queryBuilder->setAccessible(true);
        $stmt = $this->queryBuilder->invoke($this->model,$criteria);
        $this->queryBuilder->setAccessible(false);
        return $this->getSingleResult($stmt);
    }

    /**
     * Récupère toutes lignes par rapport aux critères précisés
     * @param array $criteria
     * @param array{string:string}|null $orderBy
     * @param positve-int|null $limit
     * 
     * @return AbstractModel[]
     */ 
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null):array{
        // Change l'accessibilité de la méthode pour l'execusion de la commande private => public
        $this->queryBuilder->setAccessible(true);
        $stmt = $this->queryBuilder->invoke($this->model,$criteria,$orderBy,$limit);
        $this->queryBuilder->setAccessible(false);
        return $this->getResult($stmt);
    }

    protected function createQuery(string $select = '*', ?array $criteria= null, ?array $orderBy = null, ?int $limit = null):PDOStatement{
        $this->queryBuilder->setAccessible(true);
        /**
         * @var PDOStatement $stmt
         */
        $stmt = $this->queryBuilder->invoke($this->model,$criteria,$orderBy,$limit,$select);
        $this->queryBuilder->setAccessible(false);
        return $stmt;
    }

    protected function getResult(PDOStatement $stmt):array{
        $datas = [];
        $stmt = $stmt->fetchAll();
        foreach($stmt as $data){
            if(array_key_exists("id",$data) && array_key_exists("created_at",$data)){
                /**
                 * @var AbstractModel $model
                 */
                $model = new $this->model();
                $datas[] = $model->hydrate($data);
            }
            else{
                $datas[] = array_values($data);
            }
        }
        return $datas;
    }

    protected function getSingleResult(PDOStatement $stmt):mixed{
        $data = $stmt->fetch();
        if(array_key_exists(ModelInterface::ID,$data) && array_key_exists(ModelInterface::CREATED_AT,$data)){
            return $data != false ? $this->model->hydrate($data) : null;
        }
        return count($data) > 1 ? throw new Exception("Il y'a plus d'un element !"): array_values($data)[0];
    }
}