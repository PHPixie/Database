<?php

namespace PHPixie\Database\Driver\Mongo;

class Connection extends \PHPixie\Database\Connection
{
    protected $client;
    protected $databaseName;
    protected $database;
    protected $lastInsertId;

    public function __construct($driver, $name, $config)
    {
        parent::__construct($driver, $name, $config);

        $options = $config->get('connectionOptions', array());
        if (!is_array($options))
            throw new \PHPixie\Database\Exception("Mongo 'connectionOptions' configuration parameter must be an array");

        $options['username'] = $config->get('user', '');
        $options['password'] = $config->get('password', '');

        $this->databaseName = $config->get('database');
        $options['db'] = $this->databaseName;

        $this->client = $this->connect($config->get('connection'), $options);
    }

    public function single()
    {
        return $this->driver->query('single', $this->name);
    }

    public function insertId()
    {
        return $this->lastInsertId;
    }

    public function run($runner)
    {
        $result = $runner->run($this);
        if ($result instanceof \MongoCursor)
            return $this->driver->result($result);
        return $result;
    }

    public function setInsertId($id)
    {
        $this->lastInsertId = $id;
    }

    protected function connect($connection, $options)
    {
        return new \MongoClient($connection, $options);
    }

    public function client()
    {
        return $this->client;
    }

    public function database()
    {
        if ($this->database === null)
            $this->database = $this->client()->{$this->databaseName};

        return $this->database;
    }
}
