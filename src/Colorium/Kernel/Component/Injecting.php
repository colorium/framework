<?php

namespace Colorium\Kernel\Component;

use Colorium\Http\Request;
use Colorium\Http\Response;
use Colorium\Runtime\Injector;
use Colorium\Runtime\Resolver\Resource;
use Colorium\Kernel\Component;

class Injecting extends Component
{

    /** @var Injector */
    protected $injector;


    /**
     * Define injector
     */
    public function __construct()
    {
        $this->injector = new Injector;
    }


    /**
     * Setup component
     */
    public function setup()
    {
        $this->app->injector = &$this->injector;
    }


    /**
     * Handle request/response
     *
     * @param Request $request
     * @param Response $response
     * @param callable $process
     * @return Response
     */
    public function handle(Request $request, Response $response, callable $process = null)
    {
        // if valid resolved resource
        if($request->context->resource instanceof Resource) {
            $request->context->resource->inject($this->injector);
        }

        return $process($request, $response);
    }

}