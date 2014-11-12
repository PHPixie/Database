<?php

namespace PHPixieTests\Database\Values;

/**
 * @coversDefaultClass \PHPixie\Database\Values\OrderBy
 */
class OrderbyTest extends \PHPixieTests\AbstractDatabaseTest
{
    protected $field = 'name';
    protected $direction = 'desc';

    protected $orderBy;
    
    public function setUp()
    {
        $this->orderBy = new \PHPixie\Database\Values\OrderBy($this->field, $this->direction);
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testInvalidDirectionException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Value');
        new \PHPixie\Database\Values\OrderBy($this->field, 'sideways');
    }
    
    /**
     * @covers ::field
     * @covers ::direction
     * @covers ::<protected>
     */
    public function testMethods()
    {
        $this->assertSame($this->field, $this->orderBy->field());
        $this->assertSame($this->direction, $this->orderBy->direction());
    }
}