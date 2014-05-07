<?php

namespace PHPixie\Database\Query\Data;

class Batch
{
    protected $documents;
    
    public function __construct($documents)
    {
        $this->documents = $documents;
    }
    
}