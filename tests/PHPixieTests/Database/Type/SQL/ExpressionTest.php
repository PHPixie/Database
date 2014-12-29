<?php
namespace PHPixieTests\Database\Type\SQL;

/**
 * @coversDefaultClass \PHPixie\Database\Type\SQL\Expression
 */
class ExpressionTest extends \PHPixieTests\AbstractDatabaseTest
{
    protected $expr;

    public function setUp()
    {
        $this->expr = new \PHPixie\Database\Type\SQL\Expression('a', array(1));
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
        $expr = new \PHPixie\Database\Type\SQL\Expression('b', array(2));
        $expr = $this->expr->append($expr);
        $this->assertEquals($this->expr, $expr);
        $this->assertEquals('ab', $this->expr->sql);
        $this->assertEquals(array(1, 2), $this->expr->params);
    }
}
