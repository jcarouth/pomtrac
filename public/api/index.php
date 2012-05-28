<?php
defined('BASEPATH') || define('BASEPATH', realpath(__DIR__."/../"));

/**
 * Configure autoloading from the composer library
 */
require_once BASEPATH . "/vendor/autoload.php";

$app = new Slim();

//GET route
$app->get('/', function () {
    echo 'This is a GET route';
});

//POST route
$app->post('/post', function () {
    echo 'This is a POST route';
});

//PUT route
$app->put('/put', function () {
    echo 'This is a PUT route';
});

//DELETE route
$app->delete('/delete', function () {
    echo 'This is a DELETE route';
});

$app->run();
