<?php
require_once('../init.php');
class DatabaseTest extends PHPUnit_Extensions_Database_TestCase {

    public function getConnection() {
        $database = 'deeps';
        $user = 'deeps';
        $password = 'deeps';
        $pdo = new PDO('mysql:host=localhost;dbname=deeps',$user, $password);
        return $this->createDefaultDBConnection($pdo, $database);
    }

    public function getDataSet() {
        $dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet();
        $dataSet->addTable('users',dirname(__FILE__)."/users.csv");
        return $dataSet;
    }

    public function rowsToInsert() {
        return array(
            array("users", array('username' => 'bill', 'email' => 'foo@mailinator.com'))
        );
    }

    public function rowsToDelete() {
        return array(
            array("users", array('username' => 'steve', 'email' => 'foo@mailinator.com'))
        );
    }

    /**
     * @test
     * @dataProvider rowsToInsert
     */
    public function databaseInsertion($table, array $row) {
        $current_count = $this->getConnection()->getRowCount($table);
        Database::insert($table, $row);
        $this->assertEquals($current_count+1, $this->getConnection()->getRowCount($table), "The record was not inserted into the database.");
    }

    /**
     * @test
     * @dataProvider rowsToDelete
     */
    public function databaseDeletion($table, array $row) {
        Database::insert($table, $row);
        $current_count = $this->getConnection()->getRowCount($table);
        Database::delete($table, array_slice($row,0,1));
        $this->assertEquals($current_count-1, $this->getConnection()->getRowCount($table), "The record was not deleted from the database.");
    }

    /**
     * @test
     * @dataProvider rowsToInsert
     */
    public function databaseQuery() {
    }
}
