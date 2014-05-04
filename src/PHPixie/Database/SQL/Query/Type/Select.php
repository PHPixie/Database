<?php

namespace PHPixie\Database\SQL\Query\Type;

class Select extends \PHPixie\Database\SQL\Query\Select
{
    protected $groupBy = array();
    protected $unions = array();
    
    public function fields()
        
    public function groupBy($field = null)
    {
        $this->groupBy[] = $field;

        return $this;
    }

    public function getGroupBy()
    {
        return $this->groupBy;
    }
    
    public function union($query, $all=false)
    {
        $this->unions[] = array($query, $all);
        return $this;
    }
    
    public function getUnions()
    {
        return $this->unions;
    }
}