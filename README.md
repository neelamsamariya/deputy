# Overview

Coding Challenge - User Hierarchy

This repository contains application for users hierarchy based on the roles. This application is developed using PHP 8.1 and composer installed. No database is used in this application. The sample input was provided in the coding challenge pdf which is used to develop this application.

Start by cloning/forking this repo and run `composer install` to get started.

phpunit test is written to test the functionality of the application. You can run phpunit test by running in commandline  `./vendor/bin/phpunit tests` in your application folder.

## The Tasks

Sample input

```
roles = [
    {
        "Id": 1,
        "Name": "System Administrator",
        "Parent": 0
    },
    {
        "Id": 2,
        "Name": "Location Manager",
        "Parent": 1,
    },
    {
        "Id": 3,
        "Name": "Supervisor",
        "Parent": 2,
    },
    {
        "Id": 4,
        "Name": "Employee",
        "Parent": 3,
    },
    {
        "Id": 5,
        "Name": "Trainer",
        "Parent": 3,
    }
]

users = [
    {
        "Id": 1,
        "Name": "Adam Admin",
        "Role": 1
    },
    {
        "Id": 2,
        "Name": "Emily Employee",
        "Role": 4   
    }, 
    {
        "Id": 3,
        "Name": "Sam Supervisor",
        "Role": 3
    },
    {
        "Id": 4,
        "Name": "Mary Manager",
        "Role": 2
    },
    {
        "Id": 5,
        "Name": "Steve Trainer",
        "Role": 5
    }
]
```
A composite design pattern with recursive function approach is being tried to be implemented in this application.

### 3 Main components

RoleComponent Interface

RoleGroup (composite)

Role (leaf)

and a user class

In code, both RoleGroup and Role classes implement the RoleComponent interface with different implementations for the render() method. 

RoleGroup class contains methods render(), setRoles(), getRoles(), setUsers(), getSubordinates() and getAllUsersForARole()
With render() on role object, it iterates over the roles and call each roles render based on composite or leaf class. 
It is tried to make a hierarchy of roles with users based on the sample input. Hence the the hierarchy created looks like

```
Array
(
    [id] => 1
    [name] => System Administrator
    [parent] => 0
    [users] => Array
        (
            [0] => Array
                (
                    [id] => 1
                    [name] => Adam Admin
                    [roleId] => 1
                )
        )
    [childs] => Array
        (
            [0] => Array
                (
                    [id] => 2
                    [name] => Location Manager
                    [parent] => 1
                    [users] => Array
                        (
                            [0] => Array
                                (
                                    [id] => 4
                                    [name] => Mary Manager
                                    [roleId] => 2
                                )
                        )
                    [childs] => Array
                        (
                            [0] => Array
                                (
                                    [id] => 3
                                    [name] => Supervisor
                                    [parent] => 2
                                    [users] => Array
                                        (
                                            [0] => Array
                                                (
                                                    [id] => 3
                                                    [name] => Sam Supervisor
                                                    [roleId] => 3
                                                )
                                        )
                                    [childs] => Array
                                        (
                                            [0] => Array
                                                (
                                                    [id] => 4
                                                    [name] => Employee
                                                    [parent] => 3
                                                    [users] => Array
                                                        (
                                                            [0] => Array
                                                                (
                                                                    [id] => 2
                                                                    [name] => Emily Employee
                                                                    [roleId] => 4
                                                                )
                                                        )
                                                )
                                            [1] => Array
                                                (
                                                    [id] => 5
                                                    [name] => Trainer
                                                    [parent] => 3
                                                    [users] => Array
                                                        (
                                                            [0] => Array
                                                                (
                                                                    [id] => 5
                                                                    [name] => Steve Trainer
                                                                    [roleId] => 5
                                                                )
                                                        )
                                                )
                                        )
                                )
                        )
                )
        )
)
```
setRoles() method in RoleGroup class refers to setup roles array based on the object passed

RoleGroup and Role class also extends the user class. This is done so as to set users for each role along with the role object itself rather than calling both roles and users separately.

setUsers() method will add users to a user array and attach to a specific role based on roleId.

Recursive function getsubordinates and getAllUsersForARole has been used to get all users subordinates subordinates until the last child based on the userId. 

```
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
```

getSubordinates accepts 2 parameters -> userId and Roledata array from which we need to find the users. The reason to pass the roleData array to the method is - I haven't used database so what I have done is created the object data array by specifying the inputs and passing that object to the getSubordinates as a resource. this method will first try to access to check the userId in the data. it will iterate over data['users'] and if the user is found, it will try to get the roleId for that user. Based on the roleId. Then a check is done to see if the dat has child and if it has child then it will try to get all the users for the roleId from the child.

### Example: 
```
$response = $role1->getSubordinates(3, $rolesResult);
```

Here role1 is considered as the topmost parent and we will get the subOrdinates for userId 3 and roles data array(mentioned above)

From rolesResult array above, we can see the userId 3 has a role 3, and role 3 is a parent to role4 and role5, Hence we get all the users for the role 4 and role 5
the output will be

```
Array
(
    [users] => Array
        (
            [0] => Array
                (
                    [0] => Array
                        (
                            [id] => 2
                            [name] => Emily Employee
                            [roleId] => 4
                        )

                )

            [1] => Array
                (
                    [0] => Array
                        (
                            [id] => 5
                            [name] => Steve Trainer
                            [roleId] => 5
                        )

                )

        )

)
```

Similar, $response = $role1->getSubordinates(1, $rolesResult), here userId = 1, So we get the role of user1 i.e role 1 and all its children roles in hierarchy and based on it get all users. Hence the output will be

```
Array
(
    [users] => Array
        (
            [0] => Array
                (
                    [0] => Array
                        (
                            [id] => 4
                            [name] => Mary Manager
                            [roleId] => 2
                        )

                )

            [1] => Array
                (
                    [0] => Array
                        (
                            [id] => 3
                            [name] => Sam Supervisor
                            [roleId] => 3
                        )

                )

            [2] => Array
                (
                    [0] => Array
                        (
                            [id] => 2
                            [name] => Emily Employee
                            [roleId] => 4
                        )

                )

            [3] => Array
                (
                    [0] => Array
                        (
                            [id] => 5
                            [name] => Steve Trainer
                            [roleId] => 5
                        )

                )

        )

)
```

Also if a userId passed in getSubordinates method if it does not have children roles , then empty array is returned.

To test the functionality, index.php can be run from the commandline in the project folder 
```
php index.php
```

Phpunit tests are written to cover this code. No mocks have been used.


