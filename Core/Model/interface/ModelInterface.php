<?php

namespace Core\Model\interface;

interface ModelInterface{

  const ID = "id";
  const CREATED_AT = "created_at";

    /**
     * Get the value of id
     *
     * @return integer
     */
    public function getId():int;

    /**
     * Set the value of id
     *
     * @param int $id
     * @return self
     */
    public function setId($id):self;

  /**
   * Get the value of created_at
   *
   * @return string
   */
    public function getCreated_at():string;

    /**
     * Set the value of created_at
     *
     * @return  self
     */ 
    public function setCreated_at(string $created_at):self;
}