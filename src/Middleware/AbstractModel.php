<?php

abstract class AbstractModel{

    private $db_host;
    private $db_name;
    private $db_user;
    private $db_password;
    private $db_port;

    private $table;

    //connexion string
    protected $_connexion;

    public function __construct()
    {
        // On récupère les données du .env.local pour initialiser les attributs
        $this->db_host = $_ENV["HOST_DATABASE"];
        $this->db_name = $_ENV["NAME_DATABASE"];
        $this->db_user = $_ENV["USER_DATABASE"];
        $this->db_password = $_ENV["PASSWORD_DATABASE"];
        $this->db_port = $_ENV["PASSWORD_DATABASE"];
    }

    // On ferme la connexion avant d'ouvrire une nouvelle et on créer une connexion mysqli
    public function getConnection(){
        $this->_connexion = null;
        try{
            $this->_connexion = new mysqli($this->db_host,$this->db_user, $this->db_password,$this->db_name,(int)$this->db_port);
        }catch(PDOException $exception){
            echo $exception->getMessage();
        }
    }

    public function setTable(string $table):void{
        $this->table = $table;
    }
    
    public function getTable():string{
        return $this->table;
    }

    /**
     * Requête pour récupèrer toutes les lignes d'une table
     *
     * @return object[]
     */
    public function findAll():array{
        $stmt = $this->createQueryBuilder([]);
        $results = [];
        while($obj = $stmt->fetch_object())
        {
            array_push($results,$obj);
        }
        return $results;
    }

    /**
     * Requête pour récupèrer une ligne d'une table via son id
     *
     * @param integer $id
     * @return object|null
     */
    public function find(int $id):?object{
        $stmt = $this->createQueryBuilder(["id" => $id]);
        return $stmt->fetch_object();
    }

    /**
     * Récupère une ligne par rapport aux critères précisés
     * 
     * @param array{'key':'value'} $criteria
     * @param array{'key':'value'}|NULL $orderBy
     * 
     * @return object|null
    */ 
    public function findOneBy(array $criteria,array $orderBy = null):?object{
        $stmt = $this->createQueryBuilder($criteria,$orderBy);
        return $stmt->fetch_object();
    }

    /**
     * Récupère toutes lignes par rapport aux critères précisés
     *
     * @param array{'key':'value' $criteria
     * @param array{'key':'value'}|NULL $orderBy
     * @return object[]
     */ 
    public function findBy(array $criteria,array $orderBy = null):array{
        $stmt = $this->createQueryBuilder($criteria,$orderBy);
        $results=[];
        while($obj = $stmt->fetch_object()){
            var_dump($obj);
            array_push($results,$obj);
        }
        return $results;
    }

    
    private function createQueryBuilder(array $criteria, array $orderBy = null):mysqli_result|bool{
        $conditionString = "";
        $conditionsValues = array();
        $index = 0;
        foreach ($criteria as $key => $value){
            if($index === 0){
                $conditionString .= " WHERE ";
            }
            if($index !== 0){
                $conditionString .= " AND ";
            }
            $conditionString .= $key . " = ? ";
            array_push($conditionsValues,$value);
            $index++;
        }
        if($orderBy !== NULL){
            $columnName = array_keys($orderBy)[0];
            array_push($conditionsValues,array_values($orderBy)[0]);
            $conditionString .= "ORDER BY ".$columnName. " = ?"; 
        }
        $conditionString .= ";";
        $sql = "SELECT * FROM ". $this->table . $conditionString;
        $stmt = $this->_connexion->execute_query($sql,$conditionsValues);
        $this->_connexion->close();
        return $stmt;
    }
    
}