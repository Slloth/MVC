<?php

namespace Core\Model\interface;

use DateTime;

interface ModelInterface{

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
   * @return DateTime
   */
    public function getCreated_at():DateTime;

    /**
     * Set the value of created_at
     *
     * @return  self
     */ 
    public function setCreated_at($created_at):self;
}