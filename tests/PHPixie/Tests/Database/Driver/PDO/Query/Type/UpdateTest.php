<?php
namespace PHPixie\Tests\Database\Driver\PDO\Query\Type;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Query\Type\Update
 */
class UpdateTest extends \PHPixie\Tests\Database\Driver\PDO\Query\ItemsTest
{
    protected $queryClass = '\PHPixie\Database\Driver\PDO\Query\Type\Update';
    protected $type = 'update';

    /**
     * @covers ::set
     * @covers ::clearSet
     * @covers ::getSet
     */
    public function testSet()
    {
        $this->setClearGetTest('set', array(
            array(array('test'), array(array('test'))),
        ), 'array');
    }
    
    /**
     * @covers ::increment
     * @covers ::clearIncrement
     * @covers ::getIncrement
     */
    public function testIncrement()
    {
        $this->setClearGetTest('increment', array(
            array(array('test'), array(array('test'))),
        ), 'array');
    }
}