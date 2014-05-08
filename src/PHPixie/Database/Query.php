<?php

namespace PHPixie\Database;

interface Query
{
    public function type();
    public function parse();
    public function execute();
}
