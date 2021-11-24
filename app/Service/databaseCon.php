<?php

namespace App\Service;

use MongoDB\Client as connection;
use Exception;

class databaseCon
{
    public $db;
    public function setCon($data)
    {
        $this->db = (new connection)->social->$data;
    }
         public function getCon()
         {
            return $this->db;
         }
}
