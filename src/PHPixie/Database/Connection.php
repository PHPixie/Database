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

    public function select()
    {
        return $this->driver->query('select', $this->name);
    }

    public function update()
    {
        return $this->driver->query('update', $this->name);
    }

    public function delete()
    {
        return $this->driver->query('delete', $this->name);
    }

    public function insert()
    {
        return $this->driver->query('insert', $this->name);
    }

    public function count()
    {
        return $this->driver->query('count', $this->name);
    }

    abstract public function insertId();

    public function config()
    {
        return $this->config;
    }
}
