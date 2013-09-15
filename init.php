<?php
$INIT = true;
require_once("deeps.php");

$FLOURISH = "./flourish";
require_once("$FLOURISH/fCore.php");
require_once("$FLOURISH/fEmail.php");
require_once("$FLOURISH/fMessaging.php");
require_once("$FLOURISH/fSession.php");
require_once("$FLOURISH/fValidation.php");
require_once("$FLOURISH/fException.php");
require_once("$FLOURISH/fGrammar.php");
require_once("$FLOURISH/fExpectedException.php");
require_once("$FLOURISH/fUnexpectedException.php");
require_once("$FLOURISH/fProgrammerException.php");
require_once("$FLOURISH/fTemplating.php");
require_once("$FLOURISH/fRequest.php");
require_once("$FLOURISH/fValidationException.php");
require_once("$FLOURISH/fUTF8.php");

// Templating
$template = new fTemplating();

$template->set(array(
    'game_name' => 'Deeps',
    'header'    => 'header.php',
    'footer'    => 'footer.php',
    'main'      => 'default.php'));

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

if (is_request("action", "register")) {
    // Registration Validation
    $validator = new fValidation();
    $validator->addRequiredFields('username', 'password', 'password_check', 'email');
    $validator->addEmailFields('email');
}

if (is_request("action", "login")) {
    // Login Validation
    $validator = new fValidation();
    $validator->addRequiredFields('username', 'password');
}

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
    }
}

function print_errors() {
    if ($error = fMessaging::retrieve('error')) {
        echo $error;
    }
}
