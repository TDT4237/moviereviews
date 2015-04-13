<?php

use tdt4237\webapp\models\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $this->user = new User('luckylucke', 'myshadow');
        $this->user->setId(5);
    }

    function testUser()
    {
        $user = $this->user;

        $this->assertEquals($user->getId(), 5);
        $this->assertEquals($user->getUserName(), 'luckylucke');

        $user->setId(1337);
        $this->assertEquals($user->getId(), 1337);
    }
}
