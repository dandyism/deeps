<?php
$INIT = true;
require_once("deeps.php");

function autoloader($class_name) {
    $flourish_root = "./flourish/";
    $file = $flourish_root . $class_name . '.php';
    if (file_exists($file)) {
        include $file;
        return;
    }

    throw new Exception('The class' . $class_name . ' could not be loaded.');
}

spl_autoload_register('autoloader');

// Templating
$template = new fTemplating();

$template->set(array(
    'game_name' => 'Deeps',
    'header'    => 'header.php',
    'footer'    => 'footer.php',
    'main'      => 'default.php'));

// Database
$db = new fDatabase('mysql', 'deeps', 'deeps', 'deeps');

function is_request($name, $value) {
    if (isset($_REQUEST[$name]) && $_REQUEST[$name] == $value) {
        return true;
    }

    return false;
}

if (is_request("page", "login")) {
    $template->set('main', 'login.php');
}
else if (is_request("page", "register")) {
    $template->set('main', 'register.php');
}
else {
    $template->set('main', 'default.php');
}

function password_check($value) {
    if (isset($pass_check) && $value == $pass_check) {
        return true;
    }

    return false;
}

function username_check($username) {
    $result = $db->query("SELECT COUNT(username) FROM users WHERE username='$username'");
    $row = $result->fetch_assoc();
    if ($row['COUNT(*)'] > 0) {
        return false;
    }

    return true;
}

if (is_request("action", "register")) {
    // Registration Validation
    $validator = new fValidation();
    $validator->addRequiredFields('username', 'password', 'password_check', 'email');
    $validator->addEmailFields('email');
    // TODO: Less derpy solution
    $pass_check = $_REQUEST["password_check"];

    $validator->addCallbackRule('password', 'password_check', 'The passwords do not match.');
    $validator->addCallbackRule('username', 'username_check', 'That username is taken.');
    if (validate($validator))
        register($_REQUEST['username'], $_REQUEST['password'], $_REQUEST['password_check'], $_REQUEST['email']);
}

if (is_request("action", "login")) {
    // Login Validation
    $validator = new fValidation();
    $validator->addRequiredFields('username', 'password');
    if (validate($validator))
        login($_REQUEST['username'], $_REQUEST['password']);
}

function validate($validator) {
    if (isset($validator)) {
        try {
            $errors         = $validator->validate(TRUE);
            $field_errors   = fValidationException::removeFieldNames($errors);
            if ($errors) {
                throw new fValidationException(
                    'Errors occurred.',
                    $errors
                );
            }
        } catch (fValidationException $e) {
            fMessaging::create('error', $e->getMessage());
            fMessaging::create('field_errors', $field_errors);
            return false;
        }
    }

    return true;
}

function print_errors() {
    if ($error = fMessaging::retrieve('error')) {
        echo $error;
    }
}

function register($username, $password, $password_check, $email) {

}

function login($username, $password) {

}
