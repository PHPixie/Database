<?php

namespace PHPixie\Database\SQL\Query\Type;

class Update extends \PHPixie\Database\SQL\Query\Items implements \PHPixie\Database\Query\Type\Update
{
    protected $data;
    
    public function data($data){
        $this->data = $this->driver->valuesData($data);    
    }
    
    public function getData($data){
        return $this->data;
    }
}