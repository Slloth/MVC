<?php

namespace Core\Model;

use ArrayObject;
use Core\Db\Db;
use Core\Model\interface\ModelInterface;
use DateTimeImmutable;
use ReflectionClass;
use ReflectionProperty;

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

        $isManyToMany = null;
        
        // Si le tableau de critères est non null et remplie.
        $fields = [];
        $inters = [];
        $values = [];
        
        // On ajoute au tableau de clées " = ?" qui von être remplacé par les attributs à l'execution de la requête.
        foreach ($this as $field => $value) {
            if (($field !== NULL && $field !== 'table')) {
                $inters[] = "?";
                if($value instanceof AbstractModel){
                    $fields[] = "id_".$field;
                    $values[] = $value->getId();
                    
                }else if($value instanceof ArrayObject /*&& $value[0]->getArticles()[0] instanceof $this*/){
                    $b = 'get'. ucfirst($this->table).'s';
                    $a = get_class($value[0]->$b()[0]);
                }
                else{
                    $fields[] = $field;
                    $values[] = $value;
                }
            }
        }
        $fields[] = "created_at";
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

    public function hydrate(array &$data): ?self
    {
        foreach ($data as $key => &$value) {
            if($value != null){
                $setter = "set" . ucfirst($key);
                $adder = "add" . ucfirst($key);
                if(method_exists($this,$setter)){
                    $this->$setter($value);
                    unset($data[$key]);
                }else if(method_exists($this,$adder)){
                    $this->$adder($value);
                    unset($data[$key]);
                }
                else{
                    $relationName = str_replace("Id","",$key);
                    if(property_exists($this,$relationName)){
                        foreach($data as $key => &$value){
                            if(str_contains($key,$relationName)){
                                $data[str_replace($relationName,"",$key)] = $data[$key];
                                unset($data[$key]);
                            }
                        }
                        $relationPath = "\\App\\Models\\".ucfirst($relationName);
                        $relation = new $relationPath();
                        $data[$this->table] = $this; //mappedBy
                        $relation = $relation->hydrate($data);
                    }
                }
            }
        }
        return $this;
    }
}
