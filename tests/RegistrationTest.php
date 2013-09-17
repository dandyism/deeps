<?php
require_once('../init.php');
class RegistrationTest extends PHPUnit_Framework_TestCase {

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
        $result = Registration::register();
        $this->assertFalse($result, "Invalid registration information was accepted.");
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
        $result = Registration::register();
        $this->assertTrue($result, "Valid registration information was not accepted.");
    }
}
