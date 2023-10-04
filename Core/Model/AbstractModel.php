<?php

namespace Core\Model;

use Core\Db\Db;
use PDOStatement;

abstract class AbstractModel extends Db
{

    protected string $table;

    private Db $db;


    /**
     * Execute la partie Read du CRUD
     * 
     * On créer une variable requête qui peux prendre plusieurs formes :
     * 
     * * SELECT * FROM table WHERE $criteria ORDER BY $orderby;                                 // Si il y a des critères et un orderby.
     * * SELECT * FROM table ORDER BY $orderby; || SELECT * FROM table WHERE $criteria;         // Si il y a des critères ou un orderby.
     * * SELECT * FROM table;                                                                   // Si il n'y a pas de critères et d'orderby.
     *
     * @param array|NULL $criterias
     * @param array|NULL $orderBy
     * @return PDOStatement|FALSE
     */
    public function select(?array $criterias = NULL, ?array $orderBy = NULL): PDOStatement|FALSE
    {
        $sql = "";

        // Si le tableau de critères est non null et remplie.
        if ($criterias !== NULL && $criterias !== []) {
            $criteriaKeys = [];
            $criteriaValues = [];

            $sql .= " WHERE ";

            // On ajoute au tableau de clées " = ?" qui von être remplacé par les attributs à l'execution de la requête.
            foreach ($criterias as $key => $value) {
                $criteriaKeys[] = "$key = ?";
                $criteriaValues[] = $value;
            }

            // On implode le tableau de clées en une chaine de caractères avec " AND " entre chaque clée.
            $sql .= implode(" AND ", $criteriaKeys);
        }

        // Si le tableau de d'orderBy et remplie.
        if ($orderBy !== NULL) {
            $columnName = array_keys($orderBy)[0];
            $order = array_values($orderBy)[0];         // ASC ou DESC
            $order === "ASC" ? $sql .= " ORDER BY " . $columnName . " " . $order : $sql .= " ORDER BY " . $columnName . " " . $order;
        }

        // On fini la requête et on y ajoute devant le début de la requête
        $sql .= ";";
        $sql = "SELECT * FROM " . $this->table . $sql;

        return $this->executePreparedQuery($sql, isset($criteriaValues) ? $criteriaValues : NULL);       // Condition térnaire si la variable $criteria et définie.
    }

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
     * @param integer $id
     * @return PDOStatement|FALSE
     */
    public function delete(): PDOStatement|FALSE
    {

        $sql = "DELETE FROM " . $this->table . " WHERE id = ?";

        return $this->executePreparedQuery($sql, [$this->getId()]);       // Condition térnaire si la variable $criteria et définie.
    }

    /**
     * Execute la requête créer préparer si il y'a des attributs.  Sinon execute simplement la requête 
     *
     * @param string $sql
     * @param array<string>|NULL $attr
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
     * 
     *
     * @param array $data
     * @return self
     */
    public function hydrate(array $data): self
    {
        foreach ($data as $key => $value) {
            $setter = "set" . ucfirst($key);
            method_exists($this, $setter) ? $this->$setter($value) : null;
        }
        return $this;
    }
}
