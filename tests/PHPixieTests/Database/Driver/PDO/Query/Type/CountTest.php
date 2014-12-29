<?php
namespace PHPixieTests\Database\Driver\PDO\Query\Type;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Query\Type\Count
 */
class CountTest extends \PHPixieTests\Database\Driver\PDO\Query\ItemsTest
{
    
    protected $queryClass = '\PHPixie\Database\Driver\PDO\Query\Type\Count';
    protected $type = 'count';
    
    /**
     * @covers ::execute
     */
    public function testExecute()
    {
        $query = $this->query();
        $this->parser
                ->expects($this->any())
                ->method('parse')
                ->with ($query)
                ->will($this->returnValue(new \PHPixie\Database\Type\SQL\Expression('pixie', array(5))));

        $result = $this->quickMock('\PHPixie\Database\Driver\PDO\Result', array('get'));
        $this->expectCalls($result, array('get' => array('count')), array('get'=>5));
        $this->connection
                ->expects($this->any())
                ->method('execute')
                ->with ('pixie', array(5))
                ->will($this->returnValue($result));
        $this->assertEquals(5, $query->execute());
    }
}