<?php

namespace PHPixie\Database\Driver\Mongo;

class Result extends \PHPixie\Database\Result
{
    protected $cursor;

    public function __construct($cursor)
    {
        $this->cursor = $cursor;
    }

    public function current()
    {
        if (!$this->cursor->valid())
            return null;
        $current = (object) $this->cursor->current();
        $current->{'_id'} = (string) $current->{'_id'};
        return $current;
    }

    public function key()
    {
        if (!$this->cursor->valid())
            return null;

        return $this->cursor->key();
    }

    public function valid()
    {
        return $this->cursor->valid();
    }

    public function next()
    {
        if($this->cursor->valid())
            $this->cursor->next();
    }

    public function rewind()
    {
        $this->cursor->rewind();
    }

    public function cursor()
    {
        return $this->cursor;
    }

    public function getItemField($item, $field)
    {
        $path = explode('.', $field);
        $last = count($path) - 1;
        $current = $item;

        foreach ($path as $key => $step) {
            if(!property_exists($current, $step))

                return null;
            $current=$current->$step;
        }
        
        return $current;
    }
}
