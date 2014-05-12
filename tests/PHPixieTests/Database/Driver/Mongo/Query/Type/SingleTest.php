<?php
namespace PHPixieTests\Database\Driver\Mongo\Query\Type;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Query\Type\Single
 */
class SingleTest extends \PHPixie\Database\Driver\Mongo\Query\ItemsTest
{
    protected $queryClass = '\PHPixie\Database\Driver\Mongo\Query\Type\Single';
    protected $type = 'single';

    /**
     * @covers ::fields
     * @covers ::clearFields
     * @covers ::getFields
     */
    public function testFields()
    {
        $this->setClearGetTest('fields', array(
            array(array('pixie'), array(array('pixie'))),
        ), 'array');
    }

}