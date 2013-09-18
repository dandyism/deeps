<?php
class Database {
    protected static $db;

    public function initialize() {
        $this->db = new fDatabase('mysql','deeps','deeps','deeps');
    }

    public function insert($table, array $fields) {
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
}
