<?php
namespace PHPixie\Tests\Database\Driver\Mongo\Query\Type;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Query\Type\Count
 */
class CountTest extends \PHPixie\Tests\Database\Driver\Mongo\Query\ItemsTest
{
    protected $queryClass = '\PHPixie\Database\Driver\Mongo\Query\Type\Count';
    protected $type = 'count';
}