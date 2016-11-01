<?php

namespace MongoDB\BSON;

class ObjectId
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