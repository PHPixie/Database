<?php

namespace PHPixie\Database;

abstract class Driver
{
    protected $database;
    protected $conditions;
    protected $parsers =  array();
    protected $connections = array();

    public function __construct($database)
    {
        $this->database = $database;
    }
    
    public function conditions()
    {
        if ($this->conditions === null)
            $this->conditions = $this->buildConditions();

        return $this->conditions;
    }

    public function parser($connectionName)
    {
        if (!isset($this->parsers[$connectionName]))
            $this->parsers[$connectionName] = $this->buildParserInstance($connectionName);

        return $this->parsers[$connectionName];
    }

    public function query($type = 'select', $connectionName = 'default')
    {
        $connection = $this->database->get($connectionName);
        $config     = $connection->config();
        $parser     = $this->parser($connectionName);
        $builder    = $this->queryBuilder();

        return $this->buildQuery($type, $connection, $parser, $builder);
    }

    abstract public function queryBuilder();
    abstract public function buildConditions();
    abstract public function buildConnection($name, $config);
    abstract public function buildParserInstance($connectionName);
    abstract public function buildQuery($type, $connection, $parser, $builder);
    abstract public function result($cursor);
}
