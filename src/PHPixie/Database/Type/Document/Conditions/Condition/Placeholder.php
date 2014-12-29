<?php

namespace PHPixie\Database\Type\Document\Conditions\Condition;

class Placeholder extends \PHPixie\Database\Conditions\Condition\Placeholder
{
    public function __construct($container, $allowEmpty = true)
    {
        parent::__construct($container, $allowEmpty);    
    }
    
    public function container()
    {
        return parent::container();
    }
}