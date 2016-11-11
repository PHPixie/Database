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

    public function selectQuery()
    {
        return $this->driver->query('select', $this->name);
    }

    public function updateQuery()
    {
        return $this->driver->query('update', $this->name);
    }

    public function deleteQuery()
    {
        return $this->driver->query('delete', $this->name);
    }

    public function insertQuery()
    {
        return $this->driver->query('insert', $this->name);
    }

    public function countQuery()
    {
        return $this->driver->query('count', $this->name);
    }

    public function config()
    {
        return $this->config;
    }
    
    abstract public function insertId();
    
    public function reconnect()
    {
        $this->disconnect();
    }
    
    abstract public function disconnect();
}
