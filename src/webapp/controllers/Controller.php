<?php

namespace tdt4237\webapp\controllers;

use tdt4237\webapp\Auth;

class Controller
{
    protected $app;
    
    protected $userRepository;
    protected $auth;

    public function __construct()
    {
        $this->app = \Slim\Slim::getInstance();
        $this->userRepository = $this->app->userRepository;
        $this->auth = $this->app->auth;
        $this->hash = $this->app->hash;
    }

    protected function render($template, $variables = [])
    {
        if (! $this->auth->guest()) {
            $variables['isLoggedIn'] = true;
            $variables['isAdmin'] = $this->auth->isAdmin();
            $variables['loggedInUsername'] = $_SESSION['user'];
        }

        print $this->app->render($template, $variables);
    }
}
