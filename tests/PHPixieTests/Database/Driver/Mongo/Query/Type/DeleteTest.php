<?php
namespace PHPixieTests\Database\Driver\Mongo\Query\Type;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Query\Type\Delete
 */
class DeleteTest extends \PHPixieTests\Database\Driver\Mongo\Query\ItemsTest
{
    protected $queryClass = '\PHPixie\Database\Driver\Mongo\Query\Type\Delete';
    protected $type = 'delete';
}