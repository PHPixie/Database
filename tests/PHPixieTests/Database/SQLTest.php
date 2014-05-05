<?php
namespace PHPixieTests\Database;

/**
 * @coversDefaultClass \PHPixie\Database\SQL
 */
class SQLTest extends \PHPixieTests\AbstractDatabaseTest
{
    protected $sql;
    public function setUp()
    {
        $this->sql = new \PHPixie\Database\SQL;
    }
    
    /**
     * @covers ::expression
     */
    public function testExpression()
    {
        $expression = $this->sql->expression('test', array(5));
        $this->assertInstanceOf('\PHPixie\Database\SQL\Expression', $expression);
        $this->assertEquals('test', $expression->sql);
        $this->assertEquals(array(5), $expression->params);
    }
    
    /**
     * @covers ::bulkData
     */
    public function testBulkData()
    {
        $bulkData = $this->sql->bulkData(array('test'), array(array('pixie')));
        $this->assertInstanceOf('\PHPixie\Database\SQL\Query\Data\Bulk', $bulkData);
        $this->assertEquals(array('test'), $bulkData->columns());
        $this->assertEquals(array(array('pixie')), $expression->params);
    }
}