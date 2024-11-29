<?php

namespace HM\Core\KC\Base;

use PDO;
use PDOException;

class Database
{

    private $connection;
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    // Method to run a query and return results
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    // Method to insert data into the database
    public function insert($table, $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->query($sql, $data);
        return $this->connection->lastInsertId(); // Return the last inserted ID
    }

    // Method to update data in the database
    public function update($table, $data, $where) {
        $set = [];
        foreach ($data as $column => $value) {
            $set[] = "$column = :$column";
        }
        $set = implode(", ", $set);

        $whereClause = [];
        foreach ($where as $column => $value) {
            $whereClause[] = "$column = :$column";
        }
        $whereClause = implode(" AND ", $whereClause);

        $sql = "UPDATE $table SET $set WHERE $whereClause";
        $params = array_merge($data, $where);
        $this->query($sql, $params);
    }

    // Method to delete data from the database
    public function delete($table, $where) {
        $whereClause = [];
        foreach ($where as $column => $value) {
            $whereClause[] = "$column = :$column";
        }
        $whereClause = implode(" AND ", $whereClause);

        $sql = "DELETE FROM $table WHERE $whereClause";
        $this->query($sql, $where);
    }

    // Method to fetch all rows from a table
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to fetch a single row from a table
    public function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function freshMigrate($databasePath) :bool
    {
        foreach (glob($databasePath . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . '*.sql') as $migration)
        {
            $queries = file_get_contents($migration);

            $this->connection->query($queries);
        }

        return true;
    }

    public function seed() :bool
    {


        return true;
    }

}