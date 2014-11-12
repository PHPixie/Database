<?php

namespace PHPixie\Database\Connection;

abstract class Transactable extends \PHPixie\Database\Connection
{
    protected $savepoint = 0;
    
    public function savepointTransaction($name = null)
    {
        if($name === null) {
            $name = 'savepoint_'.$this->savepoint;
            $this->savepoint++;
        }
        
        $this->createTransactionSavepoint($name);
        return $name;
    }
    
    public abstract function beginTransaction();
    public abstract function commitTransaction();
    public abstract function rollbackTransaction();
    public abstract function inTransaction();
    public abstract function rollbackTransactionTo($savepoint);
    
    protected abstract function createTransactionSavepoint($name);
}