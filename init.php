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

fTimestamp::setDefaultTimezone('America/New_York');

// Database
fORMDatabase::attach(
    new fDatabase('mysql', 'deeps', 'deeps', 'deeps')
);

// Authorization
fAuthorization::setLoginPage('/?page=login');
fAuthorization::setAuthLevels(
    array(
        'player' => 50
    )
);

// Templating
$template = new fTemplating('/opt/lampp/htdocs/');
$template->set('game_name', "Deeps");
$template->set('header', "header.php");
$template->set('footer', "footer.php");
$template->set('main', "default.php");
$template->set('hourly_turns', 5);

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
$page = fRequest::get('page', 'string');
if (fAuthorization::checkAuthLevel('player')) {
    $template->set('main', "game.php");
}
else if ($page == "register") {
    $template->set('main', "register.php");
}
else if ($page == "login") {
    $template->set('main', "login.php");
}

if ($page == "highscores") {
    $template->set('main', "highscores.php");
}

// Load user information
if (fAuthorization::checkAuthLevel('player')) {
    $user = new User(array('email' => fAuthorization::getUserToken()));
    $template->set('user', $user);
}

// Save last page load
$user = $template->get('user');
if ($user !== null) {
    $last = intval($user->getLastTurnPayout()->format('U'));
    $now = intval(date('U'));
    $delta = $now - $last;

    $turns = intval($user->getTurns());

    while ($delta >= 3600) {
        $turns += $template->get('hourly_turns');
        $delta -= 3600;
    }

    $turn_delta = $turns - intval($user->getTurns());
    $user->setTurns($turns);
    $timestamp = new fTimestamp('now');
    $timestamp = $timestamp->adjust("-$delta seconds");
    $user->setLastTurnPayout($timestamp->format('Y-m-d H:00:00'));

    if($turn_delta > 0) fMessaging::create('success', 'user', "You have received " . fGrammar::inflectOnQuantity($turn_delta, '1 turn.', '%d turns.'));
}
