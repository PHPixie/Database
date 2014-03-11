<?php

namespace PHPixie;

class DB
{
    protected $conditions;
    protected $drivers = array();
    protected $connections =  array();

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function get($connectionName = 'default')
    {
        if (!isset($this->connections[$connectionName])) {
            $config = $this->config->slice($connectionName);
            $driver = $this->driver($config->get('driver'));
            $this->connections[$connectionName] = $driver->buildConnection($connectionName, $config);
        }

        return $this->connections[$connectionName];
    }

    public function driver($name)
    {
        if (!isset($this->drivers[$name]))
            $this->drivers[$name] = $this->buildDriver($name);

        return $this->drivers[$name];
    }

    public function buildDriver($name)
    {
        $class = '\PHPixie\DB\Driver\\'.$name;
        return new $class($this);
    }

    public function query($type = 'select', $connectionName = 'default')
    {
        return $this->get($connectionName)->query($type);
    }

    public function conditions()
    {
        if ($this->conditions === null)
            $this->conditions = $this->buildConditions();

        return $this->conditions;
    }

    public function expr($sql = '', $params = array())
    {
        return new \PHPixie\DB\SQL\Expression($sql, $params);
    }
    
    protected function buildConditions()
    {
        return new \PHPixie\DB\Conditions();
    }
}