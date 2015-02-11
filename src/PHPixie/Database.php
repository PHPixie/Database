<?php

namespace PHPixie;

class Database
{
    protected $config;
    protected $values;
    protected $sql;
    protected $document;
    protected $driverClasses = array(
        'pdo'   => '\PHPixie\Database\Driver\PDO',
        'mongo' => '\PHPixie\Database\Driver\Mongo',
    );
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
            $driverName = $this->configDriverName($config);
            $driver = $this->driver($driverName);
            $this->connections[$connectionName] = $driver->buildConnection($connectionName, $config);
        }

        return $this->connections[$connectionName];
    }
    
    public function connectionDriverName($connectionName)
    {
        $config = $this->config->slice($connectionName);
        return $this->configDriverName($config);
    }
    
    public function driver($name)
    {
        if (!isset($this->drivers[$name])) {
            $this->drivers[$name] = $this->buildDriver($name);
        }
        return $this->drivers[$name];
    }

    public function buildDriver($name)
    {
        $class = $this->driverClasses[$name];
        return new $class($this);
    }

    public function query($type = 'select', $connectionName = 'default')
    {
        return $this->get($connectionName)->query($type);
    }

    public function values()
    {
        if ($this->values === null)
            $this->values = $this->buildValues();

        return $this->values;
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
    
    protected function configDriverName($config)
    {
        $driverName = $config->get('driver');
        if(!array_key_exists($driverName, $this->driverClasses)) {
            throw new \PHPixie\Database\Exception\Driver("Driver '$driverName' does not exist");
        }
        
        return $driverName;
    }
    
    protected function buildValues()
    {
        return new \PHPixie\Database\Values();
    }

    protected function buildSql()
    {
        return new \PHPixie\Database\Type\SQL();
    }
    
}
