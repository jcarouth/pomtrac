<?php
class PomTrac_Middleware_AcceptJson extends Slim_Middleware
{
    public function call()
    {
        $request = $this->app->request();

        if (false !== strpos($request->headers('Accept'), 'application/json' )) {
            $response = $this->app->response();
            $response['Content-Type'] = 'application/json';
            $this->app->view(new PomTrac_JsonView());
        }

        // pass to the next middleware
        $this->next->call();
    }
}
