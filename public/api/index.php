<?php
defined('BASEPATH') || define('BASEPATH', realpath(__DIR__."/../../"));

/**
 * Configure autoloading from the composer library
 */
require_once BASEPATH . "/vendor/autoload.php";

$app = new Slim();

$app->get('/tasks', function() {
    header("Content-type: text/json");
    print json_encode(array(
        array('summary' => 'This is a task.'),
    ));
});

$app->run();
