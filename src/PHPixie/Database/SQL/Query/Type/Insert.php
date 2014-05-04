<?php

namespace PHPixie\Database\SQL\Query\Type;

class Insert extends \PHPixie\Database\SQL\Query
{
    protected $data;
    
    public function data($data)
    {
        $this->data = $this->driver->valuesData($data);    
    }
    
    public function bulkData($columns, $rows)
    {
        $this->data = $this->driver->bulkData($columns, $rows);
    }
    
    public function getData($data)
    {
        return $this->data;
    }
}
