<?php

abstract class DriverTest extends PHPUnit_Framework_TestCase
{
    protected $driver;
    protected $pixie;
    protected $connectionStub;
    protected $db;

    public function setUp()
    {
        $this->pixie = new \PHPixie\Pixie;
        $this->pixie-> db = $this->getMock('\PHPixie\DB', array('get'), array($this->pixie));
    }

    public function testParser()
    {
        $parser = $this->driver->parser('test');
        $this->assertEquals($parser, $this->driver->parser('test'));
        $this->assertEquals($this->parserClass, get_class($parser));
    }

    public function testQuery()
    {
        $query = $this->driver->query('delete', 'test');
        $this->assertEquals($this->queryClass, get_class($query));
        $this->assertAttributeEquals($this->connectionStub, 'connection', $query);
        $this->assertAttributeEquals('config', 'config', $query);
        $this->assertAttributeInstanceOf($this->parserClass, 'parser', $query);
    }

}
