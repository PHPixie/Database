<?php

namespace PHPixieTests\SQL\Query\Implementation;

class CommonTest extends \PHPixieTests\Query\Implementation\CommonTest
{
    protected $sqlMock;
    
    public function setUp()
    {
        $this->sqlMock = $this->quickMock('\PHPixie\Database\SQL', array());
    }
    
    protected function buildCommon()
    {
        return new \PHPixie\Database\SQL\Query\Implementation\Common($this->conditionsMock, $this->sqlMock);
    }
}