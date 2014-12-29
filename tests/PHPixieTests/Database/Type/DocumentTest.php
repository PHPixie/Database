<?php

namespace PHPixieTests\Database\Type;

/**
 * @coversDefaultClass \PHPixie\Database\Type\Document
 */
class DatabaseTest extends \PHPixieTests\AbstractDatabaseTest
{
    protected $database;
    
    protected $document;
    
    public function setUp()
    {
        $this->database = $this->quickMock('\PHPixie\Database', array());
        $this->document = new \PHPixie\Database\Type\Document($this->database);
    }

    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::conditions
     * @covers ::<protected>
     */
    public function testConditions()
    {
        $conditions = $this->quickMock('\PHPixie\Database\Conditions', array());
        $this->database
            ->expects($this->at(0))
            ->method('conditions')
            ->will($this->returnValue($conditions));
        
        $documentConditions = $this->document->conditions();
        $this->assertInstanceOf('\PHPixie\Database\Type\Document\Conditions', $documentConditions);
        $this->assertAttributeSame($conditions, 'conditions', $documentConditions);
        $this->assertSame($documentConditions, $this->document->conditions());
    }
}