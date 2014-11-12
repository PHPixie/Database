<?php

namespace PHPixie\Database\Query;

abstract class Implementation implements \PHPixie\Database\Query
{
    protected $database;
    protected $conditions;
    protected $connection;
    protected $parser;
    protected $config;
    protected $aliases = array();

    public function __construct($connection, $parser, $builder)
    {
        $this->connection = $connection;
        $this->parser     = $parser;
        $this->builder    = $builder;
    }

    public function __call($method, $args)
    {
        if(!array_key_exists($method, $this->aliases))
            throw new \PHPixie\Database\Exception\Builder("Method $method does not exist.");

        return call_user_func_array(array($this, $this->aliases[$method]), $args);
    }

    public function connection()
    {
        return $this->connection;
    }
    
    abstract public function type();
    abstract public function execute();
    abstract public function parse();

}
