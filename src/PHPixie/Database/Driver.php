<?php

namespace PHPixie\Database;

abstract class Driver
{
    protected $database;
    protected $parsers =  array();
    protected $connections = array();

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function parser($connectionName)
    {
        if (!isset($this->parsers[$connectionName]))
            $this->parsers[$connectionName] = $this->buildParserInstance($connectionName);

        return $this->parsers[$connectionName];
    }

    public function queryBuilder()
    {
        $conditions = $this->database->conditions();
        return $this->buildQueryBuilder($conditions);
    }
    
    public function query($type = 'select', $connectionName = 'default')
    {
        $connection = $this->database->get($connectionName);
        $config     = $connection->config();
        $parser     = $this->parser($connectionName);
        $builder    = $this->queryBuilder();
        return $this->buildQuery($connection, $parser, $builder, $config, $type);
    }

    abstract public function buildConnection($name, $config);
    abstract public function buildParserInstance($connectionName);
    abstract public function buildQueryBuilder($driver, $conditions);
    abstract public function buildQuery($connection, $parser, $config, $type);
    abstract public function result($cursor);

}
