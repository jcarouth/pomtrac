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

use PomTrac\Helpers as Utils;

$app = new Slim(array(
    'templates.path' => BASEPATH . "/templates",
));

$app->add(new Slim_Middleware_ContentTypes());
$app->add(new PomTrac_Middleware_RestfulContext());

//configure data store
$app->hook('slim.before', function() use ($app) {
    $conn = new Mongo();
    $db = $conn->pomtrac;

    $app->dataStore = $db;
});

$app->get('/', function() use ($app) {
    $app->render('index');
});

$app->get('/tasks', function() use ($app) {
    $app->render(
        'tasks', 
        array(
            'tasks' => iterator_to_array($app->dataStore->tasks->find()),
        )
    );
});

$app->get('/tasks/:id', function($id) use ($app) {
    $task = $app->dataStore->tasks->findOne(array('_id' => new MongoId($id)));

    if (null === $task) {
        $app->response()->status(404);
    } else {
        $app->render(
            'task',
            array(
                'task' => $task,
            )
        );
    }
});

$app->post('/tasks', function() use ($app) {
    $request = $app->request();
    $response = $app->response();

    $data = $request->getBody();

    $pomData = array(
        'summary' => $data['summary'],
        'createdDate' => new MongoDate(),
        'completed' => false,
        'estimate' => null,
    );

    if(true === $app->dataStore->tasks->insert($pomData)) {
        $response->status(201);
        $resUri = "tasks/".(string)$pomData["_id"];
        $response['Location'] = Utils\buildUrl($request, $resUri);
    } else {
        $response->status(500);
    }
});

$app->put('/tasks/:id', function($id) use ($app) {
    $data = $app->request()->getBody();
    $data = $data + array('summary' => '', 'estimate' => null, 'completed' => false);

    $updateResult = $app->dataStore->tasks->update(
        array("_id" => new MongoId($id)),
        array(
            '$set' => array(
                'summary' => $data['summary'], 
                'estimate' => $data['estimate'],
                'completed' => $data['completed'],
            )
        )
    );

    if (true === $updateResult) {
        $app->response()->status(200);
    } else {
        $app->response()->status(500);
        $this->render(
            'failure',
            array('failure' => 'Could not update the task.')
        );
    }
});

$app->delete('/tasks/:id', function($id) use ($app) {
    $deleteResult = $app->dataStore->tasks->remove(
        array("_id" => new MongoId($id))
    );

    if (true === $deleteResult) {
        $app->response()->status(204);
    } else {
        $app->response()->status(500);
    }
});

$app->run();
