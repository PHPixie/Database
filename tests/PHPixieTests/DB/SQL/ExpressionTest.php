<?php
namespace PHPixieTests\DB\SQL;

/**
 * @coversDefaultClass \PHPixie\DB\SQL\Expression
 */
class ExpressionTest extends \PHPUnit_Framework_TestCase
{
    protected $expr;

    public function setUp()
    {
        $this->expr = new \PHPixie\DB\SQL\Expression('a', array(1));
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
        $expr = new \PHPixie\DB\SQL\Expression('b', array(2));
        $expr = $this->expr->append($expr);
        $this->assertEquals($this->expr, $expr);
        $this->assertEquals('ab', $this->expr->sql);
        $this->assertEquals(array(1, 2), $this->expr->params);
    }
}
