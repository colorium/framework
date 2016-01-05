<?php

namespace Colorium\Kernel\Component;

use Colorium\Kernel\Component;
use Colorium\Http\Request;
use Colorium\Http\Response;
use Colorium\Http\Error;
use Colorium\Routing\Routable;
use Colorium\Routing\Router;
use Colorium\Runtime;

class Routing extends Component
{

    /** @var Routable */
    protected $router;


    /**
     * Create router component
     *
     * @param Routable $router
     */
    public function __construct(Routable $router = null)
    {
        $this->router = $router ?: new Router;
    }


    /**
     * Setup router
     */
    public function setup()
    {
        $this->app->router = &$this->router;
        $this->app->on = function($query, $resource, array $meta = [])
        {
            $this->router->add($query, $resource, $meta);
            return $this;
        };
    }


    /**
     * Handle request/response
     *
     * @param Request $request
     * @param Response $response
     * @param callable $process
     * @return Response
     *
     * @throws Error\NotFound
     */
    public function handle(Request $request, Response $response, callable $process = null)
    {
        // skip routing if the resource is already specified
        if(!$request->context->resource) {
            $query = $request->method . ' ' . $request->uri->path;
            $route = $this->router->find($query);
            if(!$route) {
                throw new Error\NotFound('No route corresponding to query ' . $query);
            }

            $request->context->route = $route;
            $request->context->resource = $route->resource;
            $request->context->params = $route->params;
        }

        // cast default params
        if(!$request->context->params) {
            $request->context->params = [];
        }

        return $process($request, $response);
    }

}