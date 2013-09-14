<?php
require_once("deeps.php");
require_once("./flourish/fCore.php");
require_once("./flourish/fException.php");
require_once("./flourish/fUnexpectedException.php");
require_once("./flourish/fProgrammerException.php");
require_once("./flourish/fTemplating.php");

$template = new fTemplating();

$template->set(array(
    'game_name' => 'Deeps',
    'header'    => 'header.php',
    'footer'    => 'footer.php',
    'main'      => 'default.html'
));

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
