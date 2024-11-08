<?php

namespace Core\Repository;

use Core\Db\Db;
use Core\Model\AbstractModel;
use Exception;
use PDOStatement;

/**
 * Permet d'obtenir les 4 requêtes par défaut
 * * findAll(["colonne"=> {"ASC" || "DESC"} ] = null);
 * * findBy(["nom"=>"valeurs],["colonne"=> {"ASC" || "DESC"} ] = null);
 * * find(id);
 * * findOneBy(["nom"=>"valeurs],["colonne"=> {"ASC" || "DESC"} ] = null);
 * 
 * @template T of AbstractModel
 */
abstract class AbstractRepository{

    /**
     * Le model lié au Repository
     */
    public function __construct(protected string $model){}
     
    /**
     * Requête pour récupèrer toutes les lignes d'une table
     *
     * @param array{string:string}|null $orderBy
     * @param positive-int|null $limit
     * 
     * @return T[]
     */
    public function findAll(?array $orderBy = null, ?int $limit = null):array{
        $stmt = $this->select(null,$orderBy,$limit);
        return $this->getResults($stmt);
    }

    /**
     * Requête pour récupèrer une ligne d'une table via son id
     * 
     * @return T
     */
    public function find(int $id):AbstractModel{
        $stmt = $this->select(["id" =>$id]);
        return  $this->getSingleResult($stmt);
    }

    /**
     * Récupère une ligne par rapport aux critères précisés
     * 
     * @param array $criteria
     * 
     * @return T|null
     * 
    */ 
    public function findOneBy(array $criteria):AbstractModel|NULL{
        $stmt = $this->select($criteria);
        return $this->getSingleResult($stmt);
    }

    /**
     * Récupère toutes lignes par rapport aux critères précisés
     * @param array $criteria
     * @param array{string:string}|null $orderBy
     * @param positve-int|null $limit
     * 
     * @return T[]
     */ 
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null):array{
        $stmt = $this->select($criteria,$orderBy,$limit);
        return $this->getResults($stmt);
    }

    /**
     * Retourne toutes les résultats du select dans un tableau d'objet
     * 
     * @param PDOStatement $stmt
     * @return T[]
     */
    private function getResults(PDOStatement $stmt):array{
        $datas = [];
        while($data = $stmt->fetchObject($this->model)){
            array_push($datas,$data);
        }
        return $datas;
    }

    /**
     * Retourne le premier résultat en un objet
     * 
     * @param PDOStatement $stmt
     * 
     * @return T
     */
    private function getSingleResult(PDOStatement $stmt):AbstractModel{
        return $stmt->fetchObject($this->model);
    }

        /**
     * Execute la partie Read du CRUD
     * 
     * On créer une variable requête qui peux prendre plusieurs formes :
     * 
     * * SELECT * FROM table WHERE $criteria ORDER BY $orderby;                                 // Si il y a des critères et un orderby.
     * * SELECT * FROM table ORDER BY $orderby; || SELECT * FROM table WHERE $criteria;         // Si il y a des critères ou un orderby.
     * * SELECT * FROM table;                                                                   // Si il n'y a pas de critères et d'orderby.
     *
     * @param array|null $criterias
     * @param array|null $orderBy
     * @param int|null $limit
     * 
     * 
     * @return PDOStatement|FALSE
     */
    private function select(?array $criterias = NULL, ?array $orderBy = NULL, ?int $limit = NULL, string $select = '*'): PDOStatement|FALSE
    {
        $tableName = explode("\\",$this->model);
        $tableName = end($tableName);
        
        $sql = "";
    
        // Si le tableau de critères est non null et remplie.
        if ($criterias !== NULL && $criterias !== []) {
            $criteriaKeys = [];
            $criteriaValues = [];
    
            $sql .= " WHERE ";
    
            foreach ($criterias as $key => $value) {
                // Vérifie si la valeur n'est pas un tableau d'élement si oui alors effectue la même logique mais on implode par OR
                if (is_array($value)){
                    $tmpcriterias = [];
                    foreach($value as $element){
                        $tmpcriterias[] = "$key = ?";
                        $criteriaValues[] = $element;
                    }
                    $criteriaKeys[] = implode(" OR ",$tmpcriterias);
                }
                else{
                    // On ajoute au tableau de clées " = ?" qui von être remplacé par les attributs à l'execution de la requête.
                    $criteriaKeys[] = "$key = ?";
                    $criteriaValues[] = $value;
                }
            }
            // On implode le tableau de clées en une chaine de caractères avec " AND " entre chaque clée.
            $sql .= implode(" AND ", $criteriaKeys);
        }
    
        // Si le tableau de d'orderBy et remplie.
        if ($orderBy !== NULL && $orderBy !== []) {
            $criteriaValues[] = array_keys($orderBy)[0];
            $order = array_values($orderBy)[0];
            $order === "ASC" ||  $order === "DESC" ? $sql .= " ORDER BY ? " . $order : throw new Exception('$orderBy ne prend que deux réponses ASC ou DESC');
        }

        if($limit !== NULL) {
            $limit > 0 && !is_string($limit) ? $sql .= " LIMIT " . $limit : throw new Exception('$limit doit être un Entier positif.');
        }
    
        // On fini la requête et on y ajoute devant le début de la requête
        $sql .= ";";
        $sql = "SELECT $select FROM " . $tableName . $sql;
    
        return Db::executePreparedQuery($sql, isset($criteriaValues) ? $criteriaValues : NULL);       // Condition térnaire si la variable $criteria et définie.
    }
}