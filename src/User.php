<?php

namespace App;


/**
 * User class
 */
class User
{
    public $id;
    public $name;
    public $roleId;
 
    public function __construct($id = null, $name = null, $roleId = 0)
    { 
        $this->id = $id;
        $this->name = $name;  
        $this->roleId = $roleId;     
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUser($id = 0)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'roleId' => $this->roleId
           
        ];
    }
}