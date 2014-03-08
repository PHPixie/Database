<?php
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

class MongoRunnerTest extends PHPUnit_Framework_TestCase
{
    protected $runner;
    public function setUp()
    {
        $this->runner = new \PHPixie\DB\Driver\Mongo\Query\Runner();
    }

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
