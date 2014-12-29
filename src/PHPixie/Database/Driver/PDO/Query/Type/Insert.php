<?php

namespace PHPixie\Database\Driver\PDO\Query\Type;

class Insert extends \PHPixie\Database\Driver\PDO\Query\Items implements \PHPixie\Database\Type\SQL\Query\Type\Insert
{
    public function data($data)
    {
        $this->builder->setData($data);

        return $this;
    }

    public function clearData()
    {
        $this->builder->clearValue('data');

        return $this;
    }

    public function getData()
    {
        return $this->builder->getValue('data');
    }

    public function batchData($columns, $rows)
    {
        $this->builder->setBatchData($columns, $rows);

        return $this;
    }

    public function clearBatchData()
    {
        $this->builder->clearValue('batchData');

        return $this;
    }

    public function getBatchData()
    {
        return $this->builder->getValue('batchData');
    }

    public function type()
    {
        return 'insert';
    }
}
