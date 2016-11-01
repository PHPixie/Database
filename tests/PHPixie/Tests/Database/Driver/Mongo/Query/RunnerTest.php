<?php

namespace PHPixie\Tests\Database\Driver\Mongo\Query;

class InsertStub
{
    public function getInsertedIds()
    {
        return array(8);
    }
    
    public function getInsertedId()
    {
        return 7;
    }
}

class MongoRunnerStub
{
    public $a;
    public function insertOne($b)
    {
        return new InsertStub();
    }
    public function insertMany($b)
    {
        return new InsertStub();
    }
}

class MongoRunnerConnectionTestStub extends \PHPixie\Database\Driver\Mongo\Connection
{
    protected $database;
    public function __construct()
    {
        $this->database = new \stdClass;
    }

    public function setClientStub($client)
    {
        $this->client = $client;
    }
}

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Query\Runner
 */
class RunnerTest extends \PHPixie\Tests\AbstractDatabaseTest
{
    protected $runner;
    public function setUp()
    {
        $this->runner = new \PHPixie\Database\Driver\Mongo\Query\Runner();
    }

    /**
     * @covers ::getChain
     * @covers ::chainMethod
     * @covers ::chainProperty
     */
    public function testChain()
    {
        $this->runner->chainProperty('a');
        $this->runner->chainMethod('b');
        $this->runner->chainMethod('c', array(1));

        $this->assertEquals(array(
            array(
                'type' => 'property',
                'name' => 'a'
            ),
            array(
                'type' => 'method',
                'name' => 'b',
                'args' => array()
            ),
            array(
                'type' => 'method',
                'name' => 'c',
                'args' => array(1)
            )
        ),$this->runner->getChain());
    }

    /**
     * @covers ::run
     */
    public function testRun()
    {
        $this->runner->chainProperty('a');
        $this->runner->chainMethod('insertOne', array(array('_id'=>7)));
        $conn = new MongoRunnerConnectionTestStub();
        
        $conn->setClientStub(new MongoRunnerStub());
        $conn->database()->a = new MongoRunnerStub();
        $this->assertEquals(7, $this->runner->run($conn));
        $this->assertEquals(7, $conn->insertId());
    }

    /**
     * @covers ::run
     */
    public function testRunBatchInsert()
    {
        $this->runner->chainProperty('a');
        $this->runner->chainMethod('insertMany', array(array(array('_id'=>7), array('_id'=>8))));
        $conn = new MongoRunnerConnectionTestStub();
        $conn->setClientStub(new MongoRunnerStub());
        $conn->database()->a = new MongoRunnerStub();
        $this->assertEquals(8, $this->runner->run($conn));
        $this->assertEquals(8, $conn->insertId());
    }

}
