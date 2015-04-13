<?php

namespace tdt4237\webapp\controllers;

use Exception;
use tdt4237\webapp\Auth;
use tdt4237\webapp\Hash;
use tdt4237\webapp\models\Age;
use tdt4237\webapp\models\Email;
use tdt4237\webapp\models\User;
use tdt4237\webapp\validation\EditUserFormValidation;
use tdt4237\webapp\validation\RegistrationFormValidation;

class UserController extends Controller
{

    function __construct()
    {
        parent::__construct(); // explicit parent call is necessary in PHP
    }

    function index()
    {
        if ($this->auth->guest()) {
            $this->render('newUserForm.twig', []);
        } else {
            $username = $this->auth->user()->getUserName();
            $this->app->flash('info', 'You are already logged in as ' . $username);
            $this->app->redirect('/');
        }
    }

    function create()
    {
        $request = $this->app->request;
        $username = $request->post('user');
        $password = $request->post('pass');
        
        $validationErrors = new RegistrationFormValidation($username, $password);

        if (count($validationErrors) > 0) {
            $errors = join("<br>\n", $validationErrors->getValidationErrors());
            $this->app->flashNow('error', $errors);
            $this->render('newUserForm.twig', ['username' => $username]);
        } else {
            $user = new User($username, Hash::make($password));
            $this->userRepository->save($user);
            
            $this->app->flash('info', 'Thanks for creating a user. Now log in.');
            $this->app->redirect('/login');
        }
    }

    function all()
    {
        $this->render('users.twig', [
            'users' => $this->userRepository->all()
        ]);
    }

    function logout()
    {
        $this->auth->logout();
        $this->app->redirect('/?msg=Successfully logged out.');
    }

    function show($username)
    {
        $user = $this->userRepository->findByUser($username);

        $this->render('showuser.twig', [
            'user' => $user,
            'username' => $username
        ]);
    }

    function edit()
    {
        if ($this->auth->guest()) {
            $this->app->flash('info', 'You must be logged in to edit your profile.');
            $this->app->redirect('/login');
            return;
        }

        $user = $this->auth->user();

        if (!$user) {
            throw new Exception("Unable to fetch logged in user's object from db.");
        }

        if ($this->app->request->isPost()) {
            $request = $this->app->request;
            $email = $request->post('email');
            $bio = $request->post('bio');
            $age = $request->post('age');

            $validationErrors = new EditUserFormValidation($email, $bio, $age);

            if (count($validationErrors) > 0) {
                $this->app->flashNow('error', join('<br>', $validationErrors->getValidationErrors()));
            } else {
                $user->setEmail(new Email($email));
                $user->setBio($bio);
                $user->setAge(new Age($age));
                
                $this->userRepository->save($user);
                
                $this->app->flashNow('info', 'Your profile was successfully saved.');
            }
        }

        $this->render('edituser.twig', ['user' => $user]);
    }
}
