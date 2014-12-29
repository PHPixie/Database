<?php

namespace PHPixie\Database;

abstract class Type
{
    protected $database;

    public function __construct($database)
    {
        $this->database = $database;
    }
}
