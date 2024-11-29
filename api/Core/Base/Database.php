<?php

namespace HM\Core\KC\Base;

use PDO;

class Database
{

    public PDO $connection;
    public function __construct($connection)
    {
        $this->connection = $connection;
    }


    // we can implement most common used methods here like create, all, first, update, delete, and so on..



}