<?php

namespace PHPixie\Database;

abstract class Result implements \Iterator
{
    /**
     * @param string $key
     * @return array
     */
    public function asArray($key = null)
    {
        $this->rewind();
        $array = array();
        foreach ($this as $item)
            if($key !== null && isset($item->$key)){
                $array[$item->$key] = $item;
            } else {
                $array[] = $item;
            }

        return $array;
    }

    /**
     * @param $field
     * @return null
     */
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

    /**
     * @param array $fields
     * @return array
     */
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
