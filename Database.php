<?php
class Database {
    protected static $db;

    protected function build_sql_from_array(array $array, $delim = ',') {
        $sql = "";
        foreach ($array as $key => $value) {
            $value = self::$db->escape('string', $value);
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

        $columns    = self::build_sql_from_array(array_keys($fields));
        $values     = self::build_sql_from_array(array_values($fields));
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

    public function retrieve($table, array $criterion, $field = null) {
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

    public function update($table, array $criterion, array $fields) {
        if (self::$db === null) {
            Database::initialize();
        }

        $updates = self::build_sql_from_array($fields);
        $selectors = self::build_sql_from_array($criterion, 'AND');
        $query = "UPDATE TABLE $table SET $updates WHERE $selectors";
        self::$db->execute($query);
    }
}
