<?php
namespace PHPixie\Tests\Database\Stub;

class Builder
{
    public $passed = array();
    public $startGroupLogic;
    public $startGroupNegate;
    public $endGroupCalled = false;
    public $getConditionsStub;

    public function __construct()
    {
        $this->getConditionsStub = new \stdClass;
    }

    public function buildCondition()
    {
        $this->passed[] = func_get_args();
    }

    public function getConditions()
    {
        return $this->getConditionsStub;
    }

    public function startConditionGroup($logic, $negate)
    {
        $this->startGroupLogic = $logic;
        $this->startGroupNegate = $negate;
    }

    public function endGroup()
    {
        $this->endGroupCalled = true;
    }
}