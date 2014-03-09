<?php

namespace PHPixie\DB\Driver\Mongo;

class Result extends \PHPixie\DB\Result
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

        return $this->cursor->current();
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
}
