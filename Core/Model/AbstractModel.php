<?php

namespace Core\Model;

use Core\Db\Db;
use Core\Model\interface\ModelInterface;
use DateTimeImmutable;

abstract class AbstractModel extends Db implements ModelInterface
{

    protected string $table;

    /**
     * Insert into database
     *
     * @return void
     */
    public function insert() : void
    {
        $sql = "";
        
        // Si le tableau de critères est non null et remplie.
        $fields = [];
        $inters = [];
        $values = [];
        
        // On ajoute au tableau de clées " = ?" qui von être remplacé par les attributs à l'execution de la requête.
        foreach ($this as $field => $value) {
            if ($field !== NULL && $field !== 'table') {
                $fields[] = $field;
                $inters[] = "?";
                $values[] = $value;
            }
        }
        $fields[] = "createdAt";
        $inters[] = "?";
        $values[] = date_format(new DateTimeImmutable(),"Y-m-d H:i:s");
        
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

        $this->executePreparedQuery($sql, $values);
        $this->setId($this->getInstance()->lastInsertId());
    }
    
    /**
     * Update database
     *
     * @return void
     */
    public function update(): void
    {
        $sql = "";
        
        // Si le tableau de critères est non null et remplie.
        $fields = [];
        $values = [];
        
        $sql .= " SET ";
        
        // On ajoute au tableau de clées " = ?" qui von être remplacé par les attributs à l'execution de la requête.
        foreach ($this as $field => $value) {
            if ($field !== NULL && $field !== 'table' && $field !== 'db') {
                if($field != "created_at"){
                    $fields[] = "$field = ?";
                }else{
                    $fields[] = "createdAt = ?";
                }
                $values[] = !$value instanceof DateTimeImmutable? $value:$value->format("Y-m-d H:i:s");
            }
        }
        
        // On implode le tableau de clées en une chaine de caractères avec " AND " entre chaque clée.
        $sql .= implode(", ", $fields);
        $sql .= " WHERE id = ?";
        $values[] = $this->getId();
        // On fini la requête et on y ajoute devant le début de la requête
        $sql .= ";";
        $sql = "UPDATE " . $this->table . $sql;
        
        $this->executePreparedQuery($sql, $values);
    }
    
    /**
     * delete database
     *
     * @return void
     */
    public function delete(): void
    {
        
        $sql = "DELETE FROM " . $this->table . " WHERE id = ?";
        
        $this->executePreparedQuery($sql, [$this->getId()]);
    }
}
