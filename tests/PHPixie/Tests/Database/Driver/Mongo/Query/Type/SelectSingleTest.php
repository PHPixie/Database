<?php
namespace PHPixie\Tests\Database\Driver\Mongo\Query\Type;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Query\Type\SelectSingle
 */
class SingleTest extends \PHPixie\Tests\Database\Driver\Mongo\Query\ItemTest
{
    protected $queryClass = '\PHPixie\Database\Driver\Mongo\Query\Type\SelectSingle';
    protected $type = 'selectSingle';

    /**
     * @covers ::fields
     * @covers ::clearFields
     * @covers ::getFields
     */
    public function testFields()
    {
        $this->setClearGetTest('fields', array(
            array(array('pixie'), array(array('pixie'))),
        ), 'array');
    }
    
    /**
     * @covers ::execute
     */
    public function testExecute()
    {
        $query = $this->query();
        $runner = new \PHPixie\Database\Driver\Mongo\Query\Runner();
        $result = $this->quickMock('\PHPixie\Database\Driver\Mongo\Result', array('current'));
        $this->expectCalls($result, array(), array('current' => 'test'));
        $this->parser
                ->expects($this->any())
                ->method('parse')
                ->with ($query)
                ->will($this->returnValue($runner));

        $this->connection
                ->expects($this->once())
                ->method('run')
                ->will($this->returnValue($result));

        $this->assertEquals('test', $query->execute());
    }
    

}