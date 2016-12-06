<?php

namespace PHPixie;

class Database
{
    /**
     * @var \PHPixie\Slice\Type\ArrayData
     */
    protected $config;

    /**
     * @var Database\Values
     */
    protected $values;

    /**
     * @var Database\Type\SQL
     */
    protected $sql;
    protected $document;

    protected $driverClasses = array(
        'pdo'       => '\PHPixie\Database\Driver\PDO',
        'mongo'     => '\PHPixie\Database\Driver\Mongo',
        'interbase' => '\PHPixie\Database\Driver\InterBase',
    );

    /**
     * @var Database\Driver[]
     */
    protected $drivers = array();

    /**
     * @var Database\Connection[]
     */
    protected $connections =  array();

    /**
     * @param \PHPixie\Slice\Type\ArrayData $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param string $connectionName
     * @return Database\Connection
     * @throws Database\Exception\Driver
     */
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

    /**
     * @param string $connectionName
     * @return string
     * @throws Database\Exception\Driver
     */
    public function connectionDriverName($connectionName)
    {
        $config = $this->config->slice($connectionName);
        return $this->configDriverName($config);
    }

    /**
     * @param string $name
     * @return \PHPixie\Database\Driver
     */
    public function driver($name)
    {
        if (!isset($this->drivers[$name])) {
            $this->drivers[$name] = $this->buildDriver($name);
        }
        return $this->drivers[$name];
    }

    /**
     * @param string $name
     * @return \PHPixie\Database\Driver
     */
    public function buildDriver($name)
    {
        $class = $this->driverClasses[$name];
        return new $class($this);
    }

    /**
     * @return Database\Values
     */
    public function values()
    {
        if ($this->values === null)
            $this->values = $this->buildValues();

        return $this->values;
    }

    /**
     * @param string $sql
     * @param array  $params
     * @return Database\Type\SQL\Expression
     */
    public function sqlExpression($sql = '', $params = array())
    {
        return $this->sql()->expression($sql, $params);
    }

    /**
     * @return Database\Type\SQL
     */
    public function sql()
    {
        if ($this->sql === null)
            $this->sql = $this->buildSql();

        return $this->sql;
    }

    /**
     * @param \PHPixie\Slice\Type\ArrayData $config
     * @return string
     * @throws Database\Exception\Driver
     */
    protected function configDriverName($config)
    {
        $driverName = $config->get('driver');
        if(!array_key_exists($driverName, $this->driverClasses)) {
            throw new \PHPixie\Database\Exception\Driver("Driver '$driverName' does not exist");
        }

        return $driverName;
    }

    /**
     * @return Database\Values
     */
    protected function buildValues()
    {
        return new \PHPixie\Database\Values();
    }

    /**
     * @return Database\Type\SQL
     */
    protected function buildSql()
    {
        return new \PHPixie\Database\Type\SQL();
    }

}
