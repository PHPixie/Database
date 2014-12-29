<?php

namespace PHPixie\Database\Type;

class Document
{
    protected $database;
    
    protected $conditions;
    
    public function __construct($database)
    {
        $this->database = $database;
    }
    
    public function conditions()
    {
        if($this->conditions === null) {
            $this->conditions = $this->buildConditions();
        }
        
        return $this->conditions;
    }
    
    protected function buildConditions()
    {
        return new Document\Conditions($this->database->conditions());
    }
}