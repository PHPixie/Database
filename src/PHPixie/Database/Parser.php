<?php

namespace PHPixie\Database;

abstract class Parser
{
    protected $database;
    protected $driver;
    protected $config;

    public function __construct($database, $driver, $config)
    {
        $this->database  = $database;
        $this->driver = $driver;
        $this->config = $config;
    }

    /**
     * @param Query $query
     * @return \PHPixie\Database\Type\SQL\Expression
     */
    abstract public function parse($query);

}
