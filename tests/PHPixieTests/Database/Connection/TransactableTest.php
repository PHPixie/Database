<?php

namespace PHPixieTests\Database\Connection;

/**
 * @coversDefaultClass \PHPixie\Database\Connection\Transactable
 */
abstract class TransactableTest extends \PHPixieTests\Database\ConnectionTest
{
    protected $savepointPrefix = 'savepoint_';
    
    /**
     * @covers ::savepointTransaction
     * @covers ::<protected>
     */
    public function testSavepointTransaction()
    {
        $this->prepareSavepoint('test');
        $this->connection->savepointTransaction('test');
        $this->prepareSavepoint($this->savepointPrefix.'0');
        $this->connection->savepointTransaction();
        $this->prepareSavepoint($this->savepointPrefix.'1');
        $this->connection->savepointTransaction();
    }
    
    protected abstract function prepareSavepoint($name);
}