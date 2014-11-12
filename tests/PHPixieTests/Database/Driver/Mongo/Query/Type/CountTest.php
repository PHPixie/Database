<?php
namespace PHPixieTests\Database\Driver\Mongo\Query\Type;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Query\Type\Count
 */
class CountTest extends \PHPixieTests\Database\Driver\Mongo\Query\ItemsTest
{
    protected $queryClass = '\PHPixie\Database\Driver\Mongo\Query\Type\Count';
    protected $type = 'count';
}