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

        return true;
    }
}
