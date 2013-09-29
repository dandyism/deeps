<?php
/**
 * Represents a User from the database
 **/
class User extends fActiveRecord
{
    protected function configure()
    {
        fORMColumn::configureEmailColumn($this, 'email');
        fORM::registerHookCallback($this, 'post::validate()', 'User::validatePassword');
        fORM::registerHookCallback($this, 'pre::store()', 'User::hashPassword');
    }

    static public function validatePassword($object, &$values, &$old_values, &$related_records, &$cache, &$validation_messages)
    {
        if (fActiveRecord::hasOld($old_values, 'password') && fRequest::get('password') && fRequest::get('password') != fRequest::get('password_check')) {
            $validation_messages['password'] = 'Password: the passwords do not match.';
        }
    }

    static public function hashPassword($object, &$values, &$old_values, &$related_records, &$cache)
    {
        $password = $values['password'];
        if (strpos($password, "fCryptography::password_hash") === false) {
            $hash = fCryptography::hashPassword($password);
            fActiveRecord::assign($values, $old_values, 'password', $hash);
        }
    }
}
