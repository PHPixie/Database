<?php

class MongoId
{
    public $value;
    public function __construct($value){
        $this->value = $value;
    }
    
    public function __toString()
    {
        return (string) $this->value;
    }
}