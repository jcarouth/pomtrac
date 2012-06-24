<?php
defined('BASEPATH') || define('BASEPATH', realpath(__DIR__."/../"));

set_include_path(implode(
    PATH_SEPARATOR,
    array(
        BASEPATH . "/lib",
        get_include_path(),
    )
));

/**
 * Configure autoloading from the composer library
 */
$loader = require_once BASEPATH . "/vendor/autoload.php";
$loader->setUseIncludePath(true);

$app = new Slim(array(
    'templates.path' => BASEPATH . "/templates",
));

$app->add(new PomTrac_Middleware_AcceptJson());

$app->get('/', function() use ($app) {
    $app->render('index');
});

$app->get('/tasks', function() use ($app) {
    $app->render(
        'tasks', 
        array(
            'tasks' => array(
                array('summary' => 'Complete slides for LoneStarPHP.'),
                array('summary' => 'Rehearse MicroFrameworks talk.'),
            )
        )
    );
});

$app->run();
