<?php

use App\RoleGroup;
use PHPUnit\Framework\TestCase;

use App\User;
use App\Role;
use App\RoleComponent;

Class RoleGroupTest extends TestCase
{ 
    /**
     * test roles set roles method
     */
    public function testRolesSetRolesReturnsRolesObjectArray()
    {
        $role3 = new RoleGroup(3, 'Supervisor', 2);
        $role4 = new Role(4, 'Employee', 3);
        $role3->setRoles($role4);
        $roleObjArray = $role3->getRoles();      
        $this->assertIsArray($roleObjArray);
    }

    /**
     * Test roles with set users method
     */
    public function testRolesSetUsersReturnsRolesWithUsers()
    {
        $role1 = new RoleGroup(1, 'System Administrator', 0);
        $role1->setUsers(new User(1, 'Adam Admin', $role1->getId()));
        $rolesArray = $role1->render();
        $this->assertArrayHasKey('users', $rolesArray);
        $this->assertArrayNotHasKey('childs', $rolesArray);
    }

    /**
     * Test create role parent child structure
     */
    public function testRoleArraySetup()
    {        
        //given the data to setup the RoleGroupObject i.e composite branch 
        //and Role i.e leaf
        
        $role1 = new RoleGroup(1, 'System Administrator', 0);
        $role2 = new RoleGroup(2, 'Location Manager', 1);
        $role3 = new RoleGroup(3, 'Supervisor', 2);

        $role2->setRoles($role3);
        $role1->setRoles($role2);

        //then we get the roles array i.e parent and child structure
        $rolesArray = $role1->render();
        $expectedArray = [
            'id' => 1,
            'name' => 'System Administrator',
            'parent' => 0,
            'users' => [],
            'childs' => [
                [
                    
                    'id' => 2,
                    'name' => 'Location Manager',
                    'parent' => 1,
                    'users' => [],
                    'childs' => [
                        [
                            'id' => 3,
                            'name' => 'Supervisor',
                            'parent' => 2,
                            'users' => []
                        ]                        
                    ]
                ]
            ]
        ];                               
        
        $this->assertEquals($expectedArray, $rolesArray);
    }

    /**
     * Test to get all subordinates users for given user id
     */
    public function testRolesSubordinatesReturnsSubordinateUsers()
    {
        //given we creatye first roles array along with all the child roles 
        //and users attached to each roles
        $role1 = new RoleGroup(1, 'System Administrator', 0);
        $role2 = new RoleGroup(2, 'Location Manager', 1);
        $role3 = new RoleGroup(3, 'Supervisor', 2);

        $role4 = new Role(4, 'Employee', 3);
        $role5 = new Role(5, 'Trainer', 3);

        $role3->setRoles($role4);
        $role3->setRoles($role5);

        $role2->setRoles($role3);
        $role1->setRoles($role2);

        $role1->setUsers(new User(1, 'Adam Admin', $role1->getId()));
        $role4->setUsers(new User(2, 'Emily Employee', $role4->getId()));
        $role3->setUsers(new User(3, 'Sam Supervisor', $role3->getId()));
        $role2->setUsers(new User(4, 'Mary Manager', $role2->getId()));
        $role5->setUsers(new User(5, 'Steve Trainer', $role5->getId()));

        //output of $rolesResult example can be found in readme file
        $rolesResult = $role1->render();
        
        //when we pass the userId to the subordinate method
        //getSubordinates method accepts UserId, Data Array as the parameters        
        //when passed UserId, then I should get all users whose role = parent role of given userId
        $userId = 3;
        $response = $role1->getSubordinates(3, $rolesResult);

        //then we get the user list for all users whose role = parent role of given UserId 

        $expectedResult = [
                'users' => [
                    [
                        [
                            'id' => 2,
                            'name' => 'Emily Employee',
                            'roleId' => 4
                        ]
                        ],
                        [
                            [
                                'id' => 5,
                                'name' => 'Steve Trainer',
                                'roleId' => 5
                            ]
                        ]
                ]
        ];
       
        $this->assertEquals($expectedResult, $response);
    }

    /**
     * Test to check an empty array of subordinates for a given userId
     */
    public function testSubordinatesReturnsEmptyUsersArray()
    { 
        //given we creatye first roles array along with all the child roles 
        //and users attached to each roles
        
        $role3 = new RoleGroup(3, 'Supervisor', 2);

        $role4 = new Role(4, 'Employee', 3);
        $role5 = new Role(5, 'Trainer', 3);

        $role3->setRoles($role4);
        $role3->setRoles($role5);
        
        $role4->setUsers(new User(2, 'Emily Employee', $role4->getId()));
        $role3->setUsers(new User(3, 'Sam Supervisor', $role3->getId()));        
        $role5->setUsers(new User(5, 'Steve Trainer', $role5->getId()));

        //output of $rolesResult example can be found in readme file
        $rolesResult = $role3->render();        
        
        //when
        $userId = 5;
        $actual = $role3->getSubordinates(5, $rolesResult);

        //then we get the empty user list for all users whose role = parent role of given UserId        
        $this->assertEmpty($actual);

    }   
    
}