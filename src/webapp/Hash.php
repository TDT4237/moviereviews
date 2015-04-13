<?php

namespace tdt4237\webapp;

class Hash
{
    function __construct()
    {
    }

    public function make($plaintext)
    {
        return hash('sha512', $plaintext);
    }

    public function check($plaintext, $hash)
    {
        return self::make($plaintext) === $hash;
    }
}
