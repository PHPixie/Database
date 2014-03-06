<?php

namespace PHPixie\DB;

abstract class Driver
{
    protected $db;
    protected $parsers =  array();
    protected $connections = array();

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function parser($connectionName)
    {
        if (!isset($this->parsers[$connectionName]))
            $this->parsers[$connectionName] = $this->buildParserInstance($connectionName);

        return $this->parsers[$connectionName];
    }

    public function query($type = 'select', $connectionName = 'default')
    {
        $connection = $this->db->get($connectionName);
        $config     = $connection->config();
        $parser     = $this->parser($connectionName);

        return $this->buildQuery($connection, $parser, $config, $type);
    }

    abstract public function buildConnection($name, $config);
    abstract public function buildParserInstance($connectionName);
    abstract public function buildQuery($connection, $parser, $config, $type);
    abstract public function result($cursor);

}
