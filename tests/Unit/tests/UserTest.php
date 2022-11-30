<?php

use PHPUnit\Framework\TestCase;

use App\User;

Class UserTest extends TestCase
{
    public function testReturnsUser()
    {
        $user = new User(1, 'Test User', 1);

        $userArr = [
            'id' => 1,
            'name' => 'Test User',
            'roleId' => 1
        ];
        
        $getUser = $user->getUser();
        
        $this->assertEquals($userArr, $getUser);
    }
}