<?php

namespace PHPixie\Database\Driver\Mongo;

class Query extends \PHPixie\Database\Query
{
    protected $collection;
    protected $batchData;
    protected $selectSingle = false;
    
    public function collection($collection)
    {
        $this->collection = $collection;

        return $this;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function batchData($items)
    {
        $this->batchData = $items;
        
        return $this;
    }
    
    public function getBatchData()
    {
        return $this->batchData;
    }
    
    public function data($data) 
    {
        $this->batchData = null;
        return parent::data($data);
    }
    
    public function selectSingle($selectSingle = true) {
        $this->selectSingle = $selectSingle;
        return $this;
    }
    
    public function getSelectSingle() {
        return $this->selectSingle;
    }
    
    public function parse()
    {
        return $this->parser->parse($this);
    }

    public function execute()
    {
        return $this->connection->run($this->parse());
    }

}
