<?php

namespace PHPixie\Database\Query\Type;

interface Select extends \PHPixie\Database\Query\Items
{
    public function fields($fields);
    public function clearFields();
    public function getFields();
}
