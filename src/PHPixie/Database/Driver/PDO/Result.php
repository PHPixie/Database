<?php

namespace PHPixie\Database\Driver\PDO;

class Result extends \PHPixie\Database\Result
{
    protected $statement;
    protected $fetched = false;
    protected $current;
    protected $position;

    public function __construct($statement)
    {
        $this->statement = $statement;
    }

    public function current()
    {
        $this->checkFetched();

        return $this->current;
    }

    public function key()
    {
        $this->checkFetched();

        if (!$this->valid())
            return null;

        return $this->position;
    }

    public function valid()
    {
        $this->checkFetched();

        return $this->current !== null;
    }

    public function next()
    {
        $this->checkFetched();
        $this->fetchNext();
    }

    public function rewind()
    {
        if ($this->position > 0)
            throw new \PHPixie\Database\Exception("PDO statement cannot be rewound for unbuffered queries");
    }

    public function statement()
    {
        return $this->statement;
    }

    protected function checkFetched()
    {
        if (!$this->fetched) {
            $this->fetchNext();
            $this->fetched = true;
        }
    }

    protected function fetchNext()
    {
        $this->current = $this->statement->fetchObject();
        $this->fetched = true;
        if ($this->current !== false) {
            if ($this->position === null) {
                $this->position = 0;
            } else {
                $this->position++;
            }
        } else {
            $this->current = null;
            $this->statement->closeCursor();
        }
    }
}
