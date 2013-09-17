<?php
function autoloader($class_name) {
    $flourish_root = "./flourish/";
    $file = $flourish_root . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
        return;
    }

    $file = '/opt/lampp/htdocs/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
        return;
    }

    throw new Exception('The class ' . $class_name . ' could not be loaded.');
}

spl_autoload_register('autoloader');
