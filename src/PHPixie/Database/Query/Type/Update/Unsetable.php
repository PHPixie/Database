<?php

namespace PHPixie\Database\Query\Type\Update;

interface Unsetable extends \PHPixie\Database\Query\Type\Update
{
    public function _unset($keys);
    public function clearUnset();
    public function getUnset();
}