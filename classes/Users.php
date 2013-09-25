<?php
class Users {
    public function login() {
        $row = Database::retrieve_row('users', array('username' => fRequest::get('username', 'string')));

        if (fCryptography::checkPasswordHash(fRequest::get('password', 'string'), $row['password_hash'])) {
            fAuthorization::setUserAuthLevel('player');
            fAuthorization::setUserToken($row['email']);
            fMessaging::create('success', 'user', "You have successfully logged in.");
        }
        else {
            fMessaging::create('error', 'user', "The password was invalid.");
        }
    }

    public function register() {
        $validator = new fValidation();
        $validator->addRequiredFields('username', 'password', 'password_check', 'email')
                  ->addEmailFields('email');

        try {
            $errors = $validator->validate(TRUE);

            if (fRequest::get('password') != fRequest::get('password_check')) {
                $errors['password,password_check'] = 'The passwords do not match.';
            }

            if ($errors) {
                throw new fValidationException(
                    'The following problems were found:',
                    $errors
                );
            }
        } catch (fValidationException $e) {
            fMessaging::create('error', 'user', $e->getMessage());
            return false;
        }

        $password_hash = fCryptography::hashPassword(fRequest::get('password', 'string'));
        Database::insert('users', array(
            'username'          => fRequest::get('username', 'string'),
            'password_hash'     => $password_hash,
            'email'             => fRequest::get('email', 'string')
        ));

        fAuthorization::setUserAuthLevel('player');
        fAuthorization::setUserToken(fRequest::get('email', 'string'));

        fMessaging::create('success', 'user', "You have successfully registered.");
        return true;
    }
}
