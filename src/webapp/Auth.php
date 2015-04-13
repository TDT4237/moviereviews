<?php

namespace tdt4237\webapp;

use Exception;
use tdt4237\webapp\Hash;
use tdt4237\webapp\models\User;
use tdt4237\webapp\repository\UserRepository;

class Auth
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function checkCredentials($username, $password)
    {
        $user = $this->userRepository->findByUser($username);

        if ($user === null) {
            return false;
        }

        return Hash::check($password, $user->getPasswordHash());
    }

    /**
     * Check if is logged in.
     */
    public function check()
    {
        return isset($_SESSION['user']);
    }

    /**
     * Check if the person is a guest.
     */
    public function guest()
    {
        return self::check() === false;
    }

    /**
     * Get currently logged in user.
     */
    public function user()
    {
        if (self::check()) {
            return $this->userRepository->findByUser($_SESSION['user']);
        }

        throw new Exception('Not logged in but called Auth::user() anyway');
    }

    /**
     * Is currently logged in user admin?
     */
    public function isAdmin()
    {
        if ($this->check()) {
            return $_COOKIE['isadmin'] === 'yes';
        }

        throw new Exception('Not logged in but called Auth::isAdmin() anyway');
    }

    public function logout()
    {
        session_destroy();
    }
}
