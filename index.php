<?php

use App\RoleGroup;
use App\Role;
use App\User;

require_once __DIR__ . '/vendor/autoload.php';

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

$rolesResult = $role1->render();


$response = $role1->getSubordinates(1, $rolesResult);
print_r($response);











