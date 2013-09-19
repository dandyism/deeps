<?php
class Registration {
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
            return false;
        }

        Database::insert('users', array(
            'username'          => fRequest::get('username', 'string'),
            'password'          => fRequest::get('password', 'string'),
            'password_check'    => fRequest::get('password_check', 'string'),
            'email'             => fRequest::get('email', 'string')
        ));

        fAuthorization::setUserToken('player');
        return true;
    }
}
