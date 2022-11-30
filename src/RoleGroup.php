<?php

namespace App;

use App\User;

/**
 * Composite RoleGroup
 */
class RoleGroup extends User implements RoleComponent
{
    public $roles = [];
    public $users = [];    
 
    public $id;
    public $name;
    public $parent;
 
    /**
     * Constructor for RoleGroup class
     */
    public function __construct($id = null, $name = null, $parent = null)
    {    
        $this->roles = [];
        $this->users = [];
        $this->id = $id;
        $this->name = $name;
        $this->parent = $parent;
    }

    /**
     * This method will create the roles structure hirerachy(parent and child) 
     * along with users attached to each role
     */
    public function render() 
    {
        $result['id'] = $this->id;
        $result['name'] = $this->name;
        $result['parent'] = $this->parent;
        $result['users'] = $this->users;
        
        foreach ($this->roles as $roleData) {            
            $result['childs'][] = $roleData->render(); 
            
        }
        return $result;
    }

    /**
     * This method set roles in an role object and returns the list
     */
    public function setRoles(RoleComponent $roles)
    {       
        $this->roles[] = $roles;       
    }

     /**
     * This method get roles object
     */
    public function getRoles()
    {       
        return $this->roles;       
    }


    /**
     * This method sets users and returns the list of user
     */
    public function setUsers(User $user)
    {
        $this->users[] = (array) $user;
    }

   
    /**
     * This method finds subordinates for a given user id and data array
     * this is a recursive function to traverse through roles users array and find given user id and 
     * all its subordinates. Eg. userid = 3, this method finds role for userid 3 and for that role as a parent 
     * finds all users
     * 
     * @param int $userId
     * @param array $data
     */
    public function getSubordinates($userId, $data)
    {       
        if (!empty($data)) {
            $result = [];
            $roleId = 0;
           
            if (is_array($data)) {
                if (isset($data['users']) && count($data['users']) > 0) {
                    foreach($data['users'] as $users) {
                        //find with user id at level 1 parent
                        if ($users['id'] == $userId) {
                            //get role id from the given user
                            $roleId = $users['roleId'];
                            if (isset($data['childs'])) {
                                //iterate through child that have that role id
                                $this->getAllUsersForARole($data['childs'], $roleId, $result); 
                            }
                        }
                    }
                }
                
                //when roleId is not found, iterate through other children to find the users that have that roleId
                if ($roleId === 0) {
                    //means the role id is not found yet                    
                    if (!empty($data['childs'])) {
                        if (is_array($data['childs'])) {                        
                            foreach ($data['childs'] as $children) {
                                //recursive
                                return $this->getSubordinates($userId, $children);
                            }
                        }
                    }

                }
                return $result;                
            }
            
        }
    } 

    /**
     * This method will find all the user the roleId int he given data array and returns list
     * of users
     * 
     * @param array $rolesData
     * @param int $roleId
     * @param array $result
     */
    public function getAllUsersForARole($rolesData, $roleId, array &$result = [])
    {  
        if (is_array($rolesData)) {
            //if role parent id is equal to $roleId, then get the attach users
                if (isset($rolesData['parent']) && $rolesData['parent'] === $roleId) {   
                    $result['users'][] = $rolesData['users']; 
                }                
                
                
                //iterate through roles children and get attached users
                if (!empty($rolesData['childs'])) {
                    if (is_array($rolesData['childs'])) {                        
                        foreach ($rolesData['childs'] as $children) {
                            if (!empty($children['users'])) {
                                $result['users'][] = $children['users'];
                            }
                        }
                    }                   
                }

                foreach ($rolesData as $roleValues) {
                    if (is_array($roleValues)) {
                        //if the array does not have a child then its a leaf, last node of tree
                        $this->getAllUsersForARole($roleValues, $roleId, $result);
                    }
                }
        }        
    }
        
    }

    
