<?php

namespace Colorium\Kernel;

use Colorium\Http;
use Colorium\Runtime;

class Handler extends \stdClass
{

    /** @var callable[] */
    protected $components = [];


    /**
     * Kernel constructor
     *
     * @param Component ...$components
     */
    public function __construct(Component ...$components)
    {
        $components[] = new Component\Required\Executing;
        $components = array_reverse($components);
        foreach($components as $component) {
            $next = reset($this->components) ?: null;
            $component->bind($this)->setup();
            $callable = function(Http\Request $request, Http\Response $response) use($component, $next) {
                return $component->handle($request, $response, $next);
            };
            array_unshift($this->components, $callable);
        }
    }


    /**
     * Run app
     *
     * @param Http\Request $request
     * @param Http\Response $response
     * @return mixed
     */
    public function run(Http\Request $request = null, Http\Response $response = null)
    {
        // init request and response
        $request = $request ?: Http\Request::current();
        $response = $response ?: new Http\Response;

        // call components
        $process = reset($this->components);
        $response = call_user_func($process, $request, $response);

        // send response
        return $response->send();
    }


    /**
     * Run app, skip target resolving
     *
     * @param $target
     * @param Http\Request $request
     * @return mixed
     */
    public function forward($target, Http\Request $request = null)
    {
        $request = $request ?: Http\Request::current();
        $request->context->target = $target;

        return $this->run($request);
    }

}