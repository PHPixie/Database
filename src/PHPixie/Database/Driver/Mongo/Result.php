<?php

namespace PHPixie\Database\Driver\Mongo;

class Result extends \PHPixie\Database\Result
{
    protected $cursor;
    protected $iterator;

    public function __construct($cursor)
    {
        $this->cursor = $cursor;
        $this->iterator = new \IteratorIterator($cursor);
        $this->rewind();
    }

    public function current()
    {
        if (!$this->iterator->valid())
            return null;
        $current = (object) $this->iterator->current();
        $current->{'_id'} = (string) $current->{'_id'};
        return $current;
    }

    public function key()
    {
        if (!$this->iterator->valid())
            return null;

        return $this->iterator->key();
    }

    public function valid()
    {
        return $this->iterator->valid();
    }

    public function next()
    {
        if($this->iterator->valid())
            $this->iterator->next();
    }

    public function rewind()
    {
        $this->iterator->rewind();
    }

    public function cursor()
    {
        return $this->iterator;
    }

    public function getItemField($item, $field)
    {
        $path = explode('.', $field);
        $last = count($path) - 1;
        $current = (array) $item;

        foreach ($path as $key => $step) {
            if(!array_key_exists($step, $current))
                return null;
            $current=$current[$step];
        }
        
        return $current;
    }
}
