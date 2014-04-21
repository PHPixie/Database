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

    public function get($column = null)
    {
        if (!$this->valid())
            return;

        $current = $this->current();

        if ($column === null)
            $column = $this->firstColumnName($current);

        if (isset($current->$column))
            return $current->$column;
    }

    public function getColumn($column = null, $skipNulls = false)
    {
        $this->rewind();
        $values = array();
        foreach ($this as $row) {
            if ($column === null)
                $column = $this->firstColumnName($row);

            if (isset($row->$column)) {
                $values[] = $row->$column;
            }elseif(!$skipNulls)
                $values[] = null;
        }

        return $values;
    }

    protected function firstColumnName($row)
    {
        $data = get_object_vars($row);
        return key($data);
    }

    abstract public function current();
    abstract public function next();
    abstract public function valid();
    abstract public function rewind();
}
