<?php

namespace PHPixie\Database;

interface Query
{
    public function connection();
    public function type();
    public function parse();
    public function execute();
}
