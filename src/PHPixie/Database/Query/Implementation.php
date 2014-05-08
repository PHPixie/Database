<?php

namespace PHPixie\Database\Query;

abstract class Builder implements \PHPixie\Database\Query
{
    protected $database;
    protected $conditions;
    protected $connection;
    protected $parser;
    protected $config;

    protected $data = null;
    protected $fields = array();

    public function __construct($connection, $parser, $builder)
    {
        $this->connection = $connection;
        $this->parser     = $parser;
        $this->builder    = $builder;
    }

    public function parse()
    {
        return $this->parser->parse($this);
    }

    abstract public function type();
    abstract public function execute();

}
