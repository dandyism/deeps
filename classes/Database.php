<?php
class Database {
    protected static $db;

    protected function build_sql_from_array(array $array, $delim = ',') {
        $sql = "";
        foreach ($array as $key => $value) {
            $value = self::$db->escape(gettype($value), $value);
            $sql .= "$delim $key = $value";
        }

        $sql = substr($sql, strlen($delim)+1);
        return $sql;
    }

    public function initialize() {
        self::$db = new fDatabase('mysql','deeps','deeps','deeps');
    }

    public function insert($table, array $fields) {
        if (self::$db === null) {
            Database::initialize();
        }

        $columns = "";
        $values = "";
        foreach ($fields as $key => $value) {
            $value = self::$db->escape(gettype($value), $value);
            $columns .= ", $key";
            $values  .= ", $value";
        }
        
        $columns = substr($columns, 2);
        $values = substr($values, 2);
        $query = "INSERT INTO $table ($columns) VALUES ($values)";
        self::$db->execute($query);
    }

    public function delete($table, array $criterion) {
        if (self::$db === null) {
            Database::initialize();
        }

        $selectors = self::build_sql_from_array($criterion, 'AND');
        $query = "DELETE FROM $table WHERE $selectors";
        self::$db->execute($query);
    }

    public function retrieve_row($table, array $criterion, $field = null) {
        if (self::$db === null) {
            Database::initialize();
        }

        $selectors = self::build_sql_from_array($criterion, 'AND');
        $query = "SELECT * FROM $table WHERE $selectors";

        $row = self::$db->query($query)->fetchRow();

        if ($field != null) {
            return $row[$field];
        }

        return $row;
    }

    public function retrieve($table, array $criteria) {
        if (self::$db === null) {
            Database::initialize();
        }

        $selectors = "";
        foreach ($criteria as $criterion) {
            $selectors .= " AND $criterion";
        }

        $selectors = substr($selectors, 5);
        $query = "SELECT * FROM $table WHERE $selectors";
        return self::$db->query($query)->fetchAllRows();
    }

    public function update($table, array $criterion, array $fields) {
        if (self::$db === null) {
            Database::initialize();
        }

        $updates = self::build_sql_from_array($fields);
        $selectors = self::build_sql_from_array($criterion, 'AND');
        $query = "UPDATE $table SET $updates WHERE $selectors";
        self::$db->execute($query);
    }
}
