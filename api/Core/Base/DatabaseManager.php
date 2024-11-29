<?php

namespace HM\Core\KC\Base;

use \PDO;
use \PDOException;

class DatabaseManager {
    private string $databasePath;
    private string $host;
    private string $dbname;
    private string $username;
    private string $password;
    private string $port;
    private $connection = null;

    public function __construct($configurations, $databasePath)
    {
        $this->databasePath = $databasePath;
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

            $this->connection = new PDO($dsn, $this->username, $this->password);

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return new Database($this->connection ?? null);

        } catch (PDOException $e) {
            die(sprintf('Database connection failed: %s', $e->getMessage()));
        }
    }

    public function freshMigrate($migrationPath) :bool
    {
        foreach (glob($this->databasePath . DIRECTORY_SEPARATOR . $migrationPath . DIRECTORY_SEPARATOR . '*.sql') as $migration)
        {
            $queries = file_get_contents($migration);

            $this->connection->exec($queries);
        }

        return true;
    }


    public function closeConnection() :void
    {
        $this->connection = null;
    }
}
