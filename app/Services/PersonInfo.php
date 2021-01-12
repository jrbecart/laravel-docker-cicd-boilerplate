<?php

namespace App\Services;

class PersonInfo
{
    public $firstName;
    public $lastName;
    public $external;
    public $type;
    public $found;
    public $id;  
  
    public function __construct($firstName = '', $lastName = '', $external = '', $type = '', $found = false)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->external = $external;
        $this->type = $type;
        $this->found = $found;
        $this->id = null;
    }
}
