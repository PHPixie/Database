<?php
namespace PHPixieTests\Database\Driver\PDO\Query\Type;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Query\Type\Delete;
 */
class DeleteTest extends \PHPixieTests\Database\Driver\PDO\Query\ItemTest
{
    protected $queryClass = '\PHPixie\Database\Driver\PDO\Query\Type\Delete';
    protected $type = 'delete';
}