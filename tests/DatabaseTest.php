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

    /**
     * @test
     */
    public function databaseInsertion() {
        Database::insert("users", array('username' => 'bill', 'email' => 'foo@mailinator.com'));
        $this->assertEquals(2, $this->getConnection()->getRowCount('users'), "The record was not inserted into the database.");
    }

    /**
     * @test
     */
    public function databaseDeletion() {
        Database::insert("users", array('username' => 'steve', 'email' => 'foo@mailinator.com'));
        $this->assertEquals(2, $this->getConnection()->getRowCount('users'), "Pre-condition not met.");
        Database::delete("users", array('username' => 'steve'));
        $this->assertEquals(1, $this->getConnection()->getRowCount('users'), "The record was not deleted from the database.");
    }
}
