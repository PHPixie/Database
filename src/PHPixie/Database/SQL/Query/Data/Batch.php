<?php

namespace PHPixie\Database\Query\Data;

class Batch
{
    protected $columns;
    protected $rows;
    
    public function __construct($columns, $rows)
    {
        $this->columns = $columns;
        $this->rows = $rows;
    }
    
}