<?php

namespace PHPixie\Database;

abstract class Connection
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var \PHPixie\Slice\Type\ArrayData\Slice
     */
    protected $config;

    /**
     * @var Driver
     */
    protected $driver;

    /**
     * @param Driver $driver
     * @param string $name
     * @param \PHPixie\Slice\Type\ArrayData\Slice $config
     */
    public function __construct($driver, $name, $config)
    {
        $this->driver = $driver;
        $this->name   = $name;
        $this->config = $config;
    }

    /**
     * @return Query\Type\Select
     */
    public function selectQuery()
    {
        return $this->driver->query('select', $this->name);
    }

    /**
     * @return Query\Type\Update
     */
    public function updateQuery()
    {
        return $this->driver->query('update', $this->name);
    }

    /**
     * @return Query\Type\Delete
     */
    public function deleteQuery()
    {
        return $this->driver->query('delete', $this->name);
    }

    /**
     * @return Query\Type\Insert
     */
    public function insertQuery()
    {
        return $this->driver->query('insert', $this->name);
    }

    /**
     * @return Query\Type\Count
     */
    public function countQuery()
    {
        return $this->driver->query('count', $this->name);
    }

    /**
     * @return \PHPixie\Slice\Type\ArrayData\Slice
     */
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
