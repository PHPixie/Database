<?php
namespace PHPixieTests\Database\Driver\PDO\Query\Type;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Query\Type\Insert
 */
class InsertTest extends \PHPixieTests\Database\Driver\PDO\Query\ItemsTest
{
    protected $queryClass = '\PHPixie\Database\Driver\PDO\Query\Type\Insert';
    protected $type = 'insert';
    
    /**
     * @covers ::data
     * @covers ::clearData
     * @covers ::getData
     */
    public function testData()
    {
        $this->setClearGetTest('data', array(
            array(array(array('pixie' => 5))),
        ));
    }
    
    /**
     * @covers ::batchData
     * @covers ::clearBatchData
     * @covers ::getBatchData
     */
    public function testBatchData()
    {
        $this->setClearGetTest('batchData', array(
            array(array(array(5), array(6))),
        ));
    }
}