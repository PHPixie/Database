<?php

namespace PHPixie\Database;

abstract class Result implements \Iterator
{
    public function asArray($fieldAsKey = null)
    {
        $this->rewind();
        $array = array();
        foreach ($this as $item)
            if($fieldAsKey){
                $array[$item->{$fieldAsKey}] = $item;
            } else {
                $array[] = $item;
            }

        return $array;
    }

    public function get($field)
    {
        if (!$this->valid())
            return null;

        return $this->getItemField($this->current(), $field);
    }

    public function getField($field, $skipNulls = false)
    {
        $this->rewind();
        $values = array();
        foreach ($this as $item) {
            $value = $this->getItemField($item, $field);
            if ($value !== null || !$skipNulls)
                $values[] = $value;
        }

        return $values;
    }

    public function getFields($fields)
    {
        $data = array();
        foreach($this as $item){
            $values = array();
            foreach($fields as $field)
                $values[$field] = $this->getItemField($item, $field);
            $data[]=$values;
        }
        
        return $data;
    }

    public function getItemField($item, $field)
    {
        $current = $item;
        if(!property_exists($current, $field))

            return null;

        return $current->$field;
    }

    abstract public function current();
    abstract public function next();
    abstract public function valid();
    abstract public function rewind();
}
