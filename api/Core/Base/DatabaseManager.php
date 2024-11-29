<?php

namespace HM\Core\KC\Base;

use \PDO;
use \PDOException;

class DatabaseManager {
    private string $host;
    private string $dbname;
    private string $username;
    private string $password;
    private string $port;
    private ?Database $connection = null;

    public function __construct($configurations)
    {
        $this->host     = $configurations['host'];
        $this->username = $configurations['user'];
        $this->password = $configurations['pass'];
        $this->dbname   = $configurations['name'];
        $this->port     = $configurations['port'];
    }

    public function startConnection() :?Database
    {
        try {

            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8;port={$this->port}";

            $connection = new PDO($dsn, $this->username, $this->password);

            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return new Database($connection ?? null);

        } catch (PDOException $e) {
            die(sprintf('Database connection failed: %s', $e->getMessage()));
        }
    }

    public function closeConnection() :void
    {
        $this->connection = null;
    }
}
