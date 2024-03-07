<?php

namespace Core\Model;

use Core\Db\Db;
use Core\Model\interface\ModelInterface;
use Exception;
use PDOStatement;

abstract class AbstractModel extends Db implements ModelInterface
{

    protected string $table;

    private Db $db;

    /**
     * Insert into database
     *
     * @return PDOStatement|FALSE
     */
    public function insert(): PDOStatement|FALSE
    {
        $sql = "";
        
        // Si le tableau de critères est non null et remplie.
        $fields = [];
        $inters = [];
        $values = [];
        
        // On ajoute au tableau de clées " = ?" qui von être remplacé par les attributs à l'execution de la requête.
        foreach ($this as $field => $value) {
            if ($field !== NULL && $field !== 'table' && $field !== 'db') {
                $fields[] = $field;
                $inters[] = "?";
                $values[] = $value;
            }
        }
        
        // On implode le tableau de clées en une chaine de caractères avec " AND " entre chaque clée.
        $sql .= " (";
        $sql .= implode(", ", $fields);
        $sql .= ")";
        $sql .= " VALUES (";
        $sql .= implode(", ", $inters);
        $sql .= ")";
        
        // On fini la requête et on y ajoute devant le début de la requête
        $sql .= ";";
        $sql = "INSERT INTO " . $this->table . $sql;
        
        return $this->executePreparedQuery($sql, $values);       // Condition térnaire si la variable $criteria et définie.
    }
    
    /**
     * Update database
     *
     * @return PDOStatement|FALSE
     */
    public function update(): PDOStatement|FALSE
    {
        $sql = "";
        
        // Si le tableau de critères est non null et remplie.
        $fields = [];
        $values = [];
        
        $sql .= " SET ";
        
        // On ajoute au tableau de clées " = ?" qui von être remplacé par les attributs à l'execution de la requête.
        foreach ($this as $field => $value) {
            if ($field !== NULL && $field !== 'table' && $field !== 'db') {
                $fields[] = "$field = ?";
                $values[] = $value;
            }
        }
        
        // On implode le tableau de clées en une chaine de caractères avec " AND " entre chaque clée.
        $sql .= implode(", ", $fields);
        $sql .= " WHERE id = ?";
        $values[] = $this->getId();
        // On fini la requête et on y ajoute devant le début de la requête
        $sql .= ";";
        $sql = "UPDATE " . $this->table . $sql;
        
        return $this->executePreparedQuery($sql, $values);       // Condition térnaire si la variable $criteria et définie.
    }
    
    /**
     * delete database
     *
     * @return PDOStatement|FALSE
     */
    public function delete(): PDOStatement|FALSE
    {
        
        $sql = "DELETE FROM " . $this->table . " WHERE id = ?";
        
        return $this->executePreparedQuery($sql, [$this->getId()]);       // Condition térnaire si la variable $criteria et définie.
    }
    
    /**
     * Hydrate une subclass d'AbstractModel et le renvoi ou Null
     * 
     * @param array $data
     * 
     * @return AbstractModel|null
     */
    public function hydrate(array $data): ?self
    {
        foreach ($data as $key => $value) {
            $setter = "set" . ucfirst($key);
            method_exists($this, $setter) ? $this->$setter($value) : null;
        }
        return $this;
    }

    /**
     * Execute la requête créer préparer si il y'a des attributs.  Sinon execute simplement la requête 
     *
     * @param string $sql
     * @param array<string>|NULL $attr
     * 
     * @return PDOStatement|FALSE
     */
    private function executePreparedQuery(string $sql, ?array $attr = NULL): PDOStatement|FALSE
    {
        $this->db = Db::getInstance();
        if ($attr !== NULL) {
            $query = $this->db->prepare($sql);
            $query->execute($attr);
            return $query;
        } else {
            return $this->db->query($sql);
        }
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
     * @return PDOStatement|FALSE
     */
    private function select(?array $criterias = NULL, ?array $orderBy = NULL, ?int $limit = null, string $select = '*'): PDOStatement|FALSE
    {
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
        $sql = "SELECT $select FROM " . $this->table . $sql;
    
        return $this->executePreparedQuery($sql, isset($criteriaValues) ? $criteriaValues : NULL);       // Condition térnaire si la variable $criteria et définie.
    }
}
