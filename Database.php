<?php
class Database {
    protected static $db;

    public function initialize() {
        $this->db = new fDatabase('mysql','deeps','deeps','deeps');
    }

    public function insert($table, array $fields) {
        if (!isset($this->db)) {
            Database::initialize();
        }

        $columns    = "";
        $values     = "";
        foreach ($fields as $key => $value) {
            $value = $this->db->escape('string',$value);
            $columns    .= ", $key";
            $values     .= ", $value";
        }
        $columns    = substr($columns,  2);
        $values     = substr($values,   2);
        $query = "INSERT INTO $table ($columns) VALUES ($values)";
        $this->db->execute($query);
    }

    public function delete($table, array $criterion) {
        if (!isset($this->db)) {
            Database::initialize();
        }

        $query = "";
        foreach ($criterion as $key => $value) {
            $value = $this->db->escape('string',$value);
            $query .= "AND $key = $value ";
        }
        $query = substr($query, 4);
        $query = "DELETE FROM $table WHERE $query";
        $this->db->execute($query);
    }

    public function retrieve($table, array $criterion) {
        if (!isset($this->db)) {
            Database::initialize();
        }

        $query = "SELECT * FROM $table WHERE";
        foreach ($criterion as $key => $value) {
            $value = $this->db->escape('string', $value);
            $query .= " $key = $value";
        }

        return $this->db->query($query)->fetchRow();
    }
}
