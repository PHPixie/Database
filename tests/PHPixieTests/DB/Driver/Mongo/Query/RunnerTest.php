<?php

namespace PHPixieTests\DB\Driver\Mongo\Query;

class MongoRunnerStub
{
    public $a;
    public function insert($b)
    {
        return 5;
    }
}

class MongoRunnerConnectionTestStub extends \PHPixie\DB\Driver\Mongo\Connection
{
    public function setClientStub($client)
    {
        $this->client = $client;
    }
    public function __construct()
    {
    }
}

/**
 * @coversDefaultClass \PHPixie\DB\Driver\Mongo\Query\Runner
 */
class RunnerTest extends \PHPixieTests\AbstractDBTest
{
    protected $runner;
    public function setUp()
    {
        $this->runner = new \PHPixie\DB\Driver\Mongo\Query\Runner();
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
        $this->runner->chainMethod('insert', array(array('_id'=>7)));
        $conn = new MongoRunnerConnectionTestStub();
        $conn->setClientStub(new MongoRunnerStub());
        $conn->client()->a = new MongoRunnerStub();
        $this->assertEquals(5, $this->runner->run($conn));
        $this->assertEquals(7, $conn->insertId());
    }

}
