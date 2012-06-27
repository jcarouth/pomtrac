<?php
class PomTrac_Middleware_RestfulContext extends Slim_Middleware
{
    const CONTEXT_JSON = 'json';

    private $contexts = array();

    private $acceptHeaders = array();

    public function __construct($contexts = array(), $acceptHeaders = array())
    {
        $this->setContexts($contexts);
        $this->setAcceptHeaders($acceptHeaders);
    }

    public function call()
    {
        $request = $this->app->request();

        $reqContext = $this->getRequestedContext($this->app->request());

        if (null !== $reqContext) {
            $response = $this->app->response();

            foreach ($reqContext['headers'] as $header => $content) {
                $response[$header] = $content;
            }
            
            $this->app->view(new $reqContext['slimview']());
        }

        // pass to the next middleware
        $this->next->call();
    }

    private function getRequestedContext($request)
    {
        $acceptHeader = $request->headers('Accept');

        $contentTypeParts = preg_split('/\s*[;,]\s*/', $acceptHeader);
        $acceptKey = strtolower($contentTypeParts[0]);

        //assume only a single media range
        if (array_key_exists($acceptKey, $this->acceptHeaders)) {
            $contextKey = $this->acceptHeaders[$acceptKey];

            return $this->contexts[$contextKey];
        }

        return null;
    }

    private function setContexts($contexts = array())
    {
        if (empty($this->contexts)) {
            $this->contexts = array(
                self::CONTEXT_JSON => array(
                    'headers' => array(
                        'Content-type' => 'application/json',
                    ),
                    'slimview' => 'PomTrac_JsonView',
                ),
            );
        } 
    }

    private function setAcceptHeaders($headers = array()) 
    {
        if (empty($this->acceptHeaders)) {
            $this->acceptHeaders = array(
                'application/json' => self::CONTEXT_JSON,
            );
        }
    }
}
