<?php

namespace tdt4237\webapp\models;

use Slim\Slim;

class User
{
    protected $userId = null;
    protected $user;
    protected $pass;
    protected $email = null;
    protected $bio = 'Bio is empty.';
    protected $age;
    protected $isAdmin = 0;

    function __construct($user, $pass)
    {
        $this->user = $user;
        $this->pass = $pass;
    }
    
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    function getId()
    {
        return $this->userId;
    }

    function getUserName()
    {
        return $this->user;
    }

    function getPasswordHash()
    {
        return $this->pass;
    }

    function getEmail()
    {
        return $this->email;
    }

    function getBio()
    {
        return $this->bio;
    }

    function getAge()
    {
        return $this->age;
    }

    function isAdmin()
    {
        return $this->isAdmin === "1";
    }

    function setId($userId)
    {
        $this->userId = $userId;
    }

    function setUsername($username)
    {
        $this->user = $username;
    }

    function setHash($hash)
    {
        $this->pass = $hash;
    }

    function setEmail(Email $email)
    {
        $this->email = $email;
    }

    function setBio($bio)
    {
        $this->bio = $bio;
    }

    function setAge(Age $age)
    {
        $this->age = $age;
    }
}
