<?php
define('BASEPATH', realpath(__DIR__."/../"));

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

require_once BASEPATH."/lib/PomTrac/Helpers.php";

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
    $collection = $app->dataStore->tasks;
    $tasks = iterator_to_array($collection->find());
    $app->render(
        'tasks', 
        array(
            'tasks' => $tasks, 
        )
    );
});

$app->get('/tasks/:id', function($id) use ($app) {
    $collection = $app->dataStore->tasks;
    $task = $collection->findOne(
        array('_id' => new MongoId($id))
    );

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

        $resourceLocation = PomTrac\Helpers\buildUrl(
            $request,
            "tasks/".(string)$pomData["_id"]
        );
        
        $response['Location'] = $resourceLocation;
    } else {
        $response->status(500);
    }
});

$app->put('/tasks/:id', function($id) use ($app) {
    $data = $app->request()->getBody();

    $requiredFields = array(
        'summary' => '',
        'estimate' => null,
        'completed' => false,
    );

    $data = $data + $requiredFields;

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
