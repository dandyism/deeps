<?php
class Users {
    public function login() {
        $user = new User(array('username' => fRequest::get('username', 'string')));

        if (fCryptography::checkPasswordHash(fRequest::get('password', 'string'), $user->getPassword())) {
            fAuthorization::setUserAuthLevel('player');
            fAuthorization::setUserToken($user->getEmail());
            fMessaging::create('success', 'user', "You have successfully logged in.");
        }
        else {
            fMessaging::create('error', 'user', "The password was invalid.");
        }
    }

    public function register() {
        $user = new User();
        try {
            $user->populate();
            $user->store();
        } catch (fValidationException $e) {
            fMessaging::create('error', 'user', $e->getMessage());
            return false;
        }

        fAuthorization::setUserAuthLevel('player');
        fAuthorization::setUserToken(fRequest::get('email', 'string'));

        fMessaging::create('success', 'user', "You have successfully registered.");
        return true;
    }
}
