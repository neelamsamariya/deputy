<?php

namespace App;

use App\User;
/**
 * This is a leaf
 */
class Role extends User implements RoleComponent
{
   public $id;
   public $name;
   public $parent;
   public $users;

   /**
    * This is the constructor for Role class
    */
   public function __construct($id, $name, $parent)
   {
    $this->id = $id;
    $this->name = $name;
    $this->parent = $parent;
    $this->users = [];
   }

   /**
    * This method will create role array
    */
   public function render()
   {  
    return [
        'id' => $this->id,
        'name' => $this->name,
        'parent' => $this->parent,
        'users' => $this->users        
    ];   
   }

   /**
    * This method will set users and returns users array
    */
   public function setUsers(User $user)
    {
        $this->users[] = (array) $user;
    }
}