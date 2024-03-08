<?php

namespace Core\Model\interface;

use DateTimeImmutable;

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
    public function setId(int $id):self;

  /**
   * Get the value of created_at
   *
   * @return DateTimeImmutable
   */
    public function getCreated_at():DateTimeImmutable;

    /**
     * Set the value of created_at
     *
     * @return  self
     */ 
    public function setCreated_at(string $created_at):self;
}