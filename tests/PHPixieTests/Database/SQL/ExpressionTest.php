<?php
namespace PHPixieTests\Database\SQL;

/**
 * @coversDefaultClass \PHPixie\Database\SQL\Expression
 */
class ExpressionTest extends \PHPixieTests\AbstractDatabaseTest
{
    protected $expr;

    public function setUp()
    {
        $this->expr = new \PHPixie\Database\SQL\Expression('a', array(1));
    }

    public function testProperties()
    {
        $this->assertEquals('a', $this->expr->sql);
        $this->assertEquals(array(1), $this->expr->params);
    }

    /**
     * @covers ::append
     */
    public function testAppend()
    {
        $expr = new \PHPixie\Database\SQL\Expression('b', array(2));
        $expr = $this->expr->append($expr);
        $this->assertEquals($this->expr, $expr);
        $this->assertEquals('ab', $this->expr->sql);
        $this->assertEquals(array(1, 2), $this->expr->params);
    }
}
