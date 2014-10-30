<?php

namespace PHPixie\Database\Connection;

abstract class Transactable extends \PHPixie\Database\Connection
{
    public abstract function beginTransaction();
    public abstract function commitTransaction();
    public abstract function rollbackTransaction($savepoint = null);
    public function savepointTransaction($name = null);
    public function isTransactionActive();
}