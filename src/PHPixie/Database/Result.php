<?php

namespace PHPixie\Database;

abstract class Result implements \Iterator
{
    public function asArray()
    {
        $this->rewind();
        $arr = array();
        foreach ($this as $row)
            $arr[] = $row;

        return $arr;
    }

    public function get($field = null)
    {
        if (!$this->valid())
            return null;

        if ($field === null) {
            $current = $this->current();
            $field = $this->firstFieldName($current);
        }
        
        return $this->getCurrentField($field);
    }

    public function getField($field = null, $skipNulls = false)
    {
        $this->rewind();
        $values = array();
        foreach ($this as $row) {
            if ($field === null)
                $field = $this->firstFieldName($row);

            if (isset($row->$field)) {
                $values[] = $row->$field;
            }elseif(!$skipNulls)
                $values[] = null;
        }

        return $values;
    }

    protected function getCurrentField($field)
    {
        $current = $this->current();
        if(!property_exists($current, $field))
            return null;
        
        return $current->$field;
    }
    
    protected function firstFieldName($row)
    {
        $data = get_object_vars($row);

        return key($data);
    }

    abstract public function current();
    abstract public function next();
    abstract public function valid();
    abstract public function rewind();
}
