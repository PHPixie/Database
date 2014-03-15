<?php

namespace PHPixie\Database;

abstract class Connection
{
    protected $name;
    protected $config;
    protected $driver;

    public function __construct($driver, $name, $config)
    {
        $this->driver = $driver;
        $this->name   = $name;
        $this->config = $config;
    }

    public function query($type = 'select')
    {
        return $this->driver->query($type, $this->name);
    }

    abstract public function insertId();

    public function config()
    {
        return $this->config;
    }
}
