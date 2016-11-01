<?php

namespace PHPixie\Database\Driver\Mongo;

class Connection extends \PHPixie\Database\Connection
{
    protected $client;
    protected $databaseName;
    protected $database;
    protected $lastInsertId;

    public function connect()
    {
        $config = $this->config;
        
        $uriOptions = $config->get('uriOptions', array());
        $driverOptions = $config->get('driverOptions', array());
        
        if (!is_array($uriOptions)) {
            throw new \PHPixie\Database\Exception("Mongo 'uriOptions' configuration parameter must be an array");
        }
        
        if (!is_array($driverOptions)) {
            throw new \PHPixie\Database\Exception("Mongo 'driverOptions' configuration parameter must be an array");
        }

        $uriOptions['username'] = $config->get('user', '');
        $uriOptions['password'] = $config->get('password', '');

        $this->databaseName = $config->get('database', null);
        $uriOptions['database'] = $this->databaseName;
        
        $this->client = $this->buildMongoClient($config->get('connection'), $uriOptions, $driverOptions);
    }
    
    public function disconnect()
    {
        $this->client->close();
        $this->client = null;
    }

    public function selectSingleQuery()
    {
        return $this->driver->query('selectSingle', $this->name);
    }

    public function insertId()
    {
        return $this->lastInsertId;
    }

    public function run($runner)
    {
        $result = $runner->run($this);
        if ($result instanceof \MongoDB\Driver\Cursor) {
            return $this->driver->result($result);
        }
        return $result;
    }

    public function setInsertId($id)
    {
        $this->lastInsertId = $id;
    }

    protected function buildMongoClient($connection, $uriOptions, $driverOptions)
    {
        return new \MongoDB\Client($connection, $uriOptions, $driverOptions);
    }

    public function client()
    {
        return $this->client;
    }

    public function database()
    {
        if ($this->database === null) {
            $this->database = $this->client()->{$this->databaseName};
        }

        return $this->database;
    }
}
