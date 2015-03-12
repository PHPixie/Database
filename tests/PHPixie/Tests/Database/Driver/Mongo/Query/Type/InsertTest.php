<?php
namespace PHPixie\Tests\Database\Driver\Mongo\Query\Type;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Query\Type\Insert
 */
class InsertTest extends \PHPixie\Tests\Database\Driver\Mongo\QueryTest
{
    protected $queryClass = '\PHPixie\Database\Driver\Mongo\Query\Type\Insert';
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
            array(array(array(5))),
        ));
    }
}