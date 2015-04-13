<?php

namespace tdt4237\webapp\validation;

class EditUserFormValidation implements \Countable
{
    private $validationErrors = [];
    
    public function __construct($email, $bio, $age)
    {
        $this->validate($email, $bio, $age);
    }
    
    public function count()
    {
        return \count($this->validationErrors);
    }
    
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    private function validate($email, $bio, $age)
    {
        $this->validateAge($age);
        $this->validateEmail($email);
    }
    
    private function validateEmail($email)
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->validationErrors[] = "Invalid email format on email";
        }
    }
    
    private function validateAge($age)
    {
        if (! is_numeric($age) or $age < 0 or $age > 130) {
            $this->validationErrors[] = 'Age must be between 0 and 130.';
        }
    }
}
