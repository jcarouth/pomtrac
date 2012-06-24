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

$app->add(new PomTrac_Middleware_RestfulContext());

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

$app->post('/tasks', function() use ($app) {
    $request = $app->request();

    $taskData = json_decode($request->getBody());

    //save task to data store
    $response = $app->response();
    $response->status(201);
    $response['Location'] = sprintf(
        "%s://%s/%s/%d",
        $request->getScheme(),
        $request->getHost(),
        "tasks",
        3
    );
});

$app->run();
