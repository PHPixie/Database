<?php

namespace PHPixie;

class Database
{

    protected $config;
    protected $conditions;
    protected $values;
    protected $sql;
    protected $document;
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
        $class = '\PHPixie\Database\Driver\\'.$name;

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
    
    public function values()
    {
        if ($this->values === null)
            $this->values = $this->buildValues();

        return $this->values;
    }
    
    protected function buildValues()
    {
        return new \PHPixie\Database\Values();
    }

    protected function buildConditions()
    {
        return new \PHPixie\Database\Conditions();
    }

    public function sqlExpression($sql = '', $params = array())
    {
        return $this->sql()->expression($sql, $params);
    }
    
    public function sql()
    {
        if ($this->sql === null)
            $this->sql = $this->buildSql();

        return $this->sql;
    }

    protected function buildSql()
    {
        return new \PHPixie\Database\Type\SQL();
    }
    
    public function document()
    {
        if ($this->document === null)
            $this->document = $this->buildDocument();

        return $this->document;
    }
    
    protected function buildDocument()
    {
        return new \PHPixie\Database\Type\Document($this);
    }
    


}
