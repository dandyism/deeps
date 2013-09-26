<?php
function autoloader($class_name) {
    $flourish_root = "/opt/lampp/htdocs/flourish/";
    $file = $flourish_root . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
        return;
    }

    $file = '/opt/lampp/htdocs/classes/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
        return;
    }

    throw new Exception('The class ' . $class_name . ' could not be loaded.');
}

spl_autoload_register('autoloader');

fAuthorization::setLoginPage('/?page=login');
fAuthorization::setAuthLevels(
    array(
        'player' => 50
    )
);

$template = new fTemplating('/opt/lampp/htdocs/');
$template->set('game_name', "Deeps");
$template->set('header', "header.php");
$template->set('footer', "footer.php");
$template->set('main', "default.php");

// User Actions
$action = fRequest::get('action', 'string');
if ($action == "register") {
    Users::register();
}
else if ($action == "login") {
    Users::login();
}
else if ($action == "logout") {
    fAuthorization::destroyUserInfo();
}

// Page loads
if (fAuthorization::checkAuthLevel('player')) {
    $template->set('main', "game.php");
}
else if (fRequest::get('page', 'string') == "register") {
    $template->set('main', "register.php");
}
else if (fRequest::get('page', 'string') == "login") {
    $template->set('main', "login.php");
}
