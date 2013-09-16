<?php
$INIT = true;
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

// Authorization
fAuthorization::setLoginPage('?page=login');
fAuthorization::setAuthLevels(
    array(
        'player' => 50
    )
);

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
else if (fAuthorization::checkLoggedIn()) {
    $template->set('main', 'game.php');
}
else {
    $template->set('main', 'default.php');
}

function password_check($value) {
    global $pass_check;
    if (isset($pass_check) && $value == $pass_check) {
        return true;
    }

    return false;
}

function username_check($username) {
    global $db;
    $result = $db->query("SELECT COUNT(username) FROM users WHERE username='%s'",$username);
    $row = $result->fetchRow();
    if ($row['COUNT(username)'] > 0) {
        return false;
    }

    return true;
}

if (is_request("action", "register")) {
        register($_REQUEST['username'], $_REQUEST['password'], $_REQUEST['email']);
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

function register($username, $password, $email) {
   global $db, $pass_check;

    // Registration Validation
    $validator = new fValidation();
    $validator->addRequiredFields('username', 'password', 'password_check', 'email');
    $validator->addEmailFields('email');
    // TODO: Less derpy solution
    $pass_check = $_REQUEST["password_check"];

    $validator->addCallbackRule('password', 'password_check', 'The passwords do not match.');
    $validator->addCallbackRule('username', 'username_check', 'That username is taken.');

    if (validate($validator)) {
        $hash = fCryptography::hashPassword($password);
        $db->execute("INSERT INTO users (username,password_hash,email) VALUES(%s,%s,%s)", $username, $hash, $email);
        login($username, $password); 
    }
    else {
        return false;
    }

    return true;
}

function login($username, $password) {
    global $db;
    $result = $db->query("SELECT id, password_hash FROM users WHERE username=%s", $username);
    if($row = $result->fetchRow()) {
        if (!fCryptography::checkPasswordHash($password, $row['password_hash'])) {
            fMessaging::create('error', "The password was invalid.");
            return false;
        }

        fAuthorization::setUserAuthLevel('player');
        fSession::set('player_id', $row['id']);
    }
    else {
        fMessaging::create('error', "That user does not exist.");
        return false;
    }
}
