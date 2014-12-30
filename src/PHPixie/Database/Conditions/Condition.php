<?php

namespace PHPixie\Database\Conditions;

interface Condition
{
    public function logic();
    public function setLogic($logic);
    
    public function isNegated();
    public function setIsNegated($negated);
    public function negate();
}