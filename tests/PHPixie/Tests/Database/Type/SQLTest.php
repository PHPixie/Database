<?php

namespace PHPixie\Tests\Database\Type;

/**
 * @coversDefaultClass \PHPixie\Database\Type\SQL
 */
class SQLTest extends \PHPixie\Tests\AbstractDatabaseTest
{
    protected $sql;
    
    public function setUp()
    {
        $this->sql = new \PHPixie\Database\Type\SQL();
    }
    
    /**
     * @covers ::expression
     * @covers ::<protected>
     */
    public function testExpression()
    {
        $expr = $this->sql->expression();
        $this->assertInstanceOf('\PHPixie\Database\Type\SQL\Expression', $expr);
        $this->assertEquals('', $expr->sql);
        $this->assertEquals(array(), $expr->params);
        $expr = $this->sql->expression('pixie', array('test'));
        $this->assertEquals('pixie', $expr->sql);
        $this->assertEquals(array('test'), $expr->params);
    }
}