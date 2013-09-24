<?php
class Database {
    protected static $db;

    public function initialize() {
        self::$db = new fDatabase('mysql','deeps','deeps','deeps');
    }

    public function insert($table, array $fields) {
        if (self::$db === null) {
            Database::initialize();
        }

        $columns    = "";
        $values     = "";
        foreach ($fields as $key => $value) {
            $value = self::$db->escape('string',$value);
            $columns    .= ", $key";
            $values     .= ", $value";
        }
        $columns    = substr($columns,  2);
        $values     = substr($values,   2);
        $query = "INSERT INTO $table ($columns) VALUES ($values)";
        self::$db->execute($query);
    }

    public function delete($table, array $criterion) {
        if (self::$db === null) {
            Database::initialize();
        }

        $query = "";
        foreach ($criterion as $key => $value) {
            $value = self::$db->escape('string',$value);
            $query .= "AND $key = $value ";
        }
        $query = substr($query, 4);
        $query = "DELETE FROM $table WHERE $query";
        self::$db->execute($query);
    }

    public function retrieve($table, array $criterion, $row = null) {
        if (self::$db === null) {
            Database::initialize();
        }

        $query = "SELECT * FROM $table WHERE";
        foreach ($criterion as $key => $value) {
            $value = self::$db->escape('string', $value);
            $query .= " $key = $value";
        }

        $row = self::$db->query($query)->fetchRow();

        if ($field != null) {
            return $row[$field];
        }

        return $row;
    }
}
