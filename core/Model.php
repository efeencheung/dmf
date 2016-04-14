<?php

namespace DMF;

class Model
{
    protected $conn;

    public function __construct($dbconnection)
    {
        $this->conn = $dbconnection;
    }
}
