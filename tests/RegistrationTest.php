<?php
require_once('../init.php');
class RegistrationTest extends PHPUnit_Extensions_Database_TestCase {

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

    public function invalidRegistrations() {
        return array(
            array('foobar','foobiz','potato','foo@mail.com'),
            array('foobar','potato','potato','mail.com'),
            array('',null,'potato','mail.com'),
        );
    }

    public function validRegistrations() {
        return array(
            array('foobar','potato','potato','foo@mail.com')
        );
    }

    /**
     * @test
     * @dataProvider invalidRegistrations
     */
    public function registerWithInvalidInformation($username, $password, $password_check, $email) {
        $_SERVER['REQUEST_METHOD'] = 'post';
        fRequest::set('username', $username);
        fRequest::set('password', $password);
        fRequest::set('password_check', $password_check);
        fRequest::set('email', $email);
        $current_count = $this->getConnection()->getRowCount('users');
        $result = Registration::register();
        $this->assertFalse($result, "Invalid registration information was accepted.");
        $this->assertEquals($current_count, $this->getConnection()->getRowCount('users'), "Invalid user data was inserted into the database.");
    }

    /**
     * @test
     * @dataProvider validRegistrations
     */
    public function registerWithValidInformation($username, $password, $password_check, $email) {
        $_SERVER['REQUEST_METHOD'] = 'post';
        fRequest::set('username', $username);
        fRequest::set('password', $password);
        fRequest::set('password_check', $password_check);
        fRequest::set('email', $email);
        $current_count = $this->getConnection()->getRowCount('users');
        $result = Registration::register();
        $this->assertTrue($result, "Valid registration information was not accepted.");
        $this->assertEquals($current_count+1, $this->getConnection()->getRowCount('users'), "Valid user data was not inserted into the database.");
    }
}
