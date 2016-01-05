<?php

namespace Colorium\Kernel;

use Colorium\Http\Request;
use Colorium\Http\Response;

abstract class Component
{

    /** @var Handler */
    protected $app;

    /**
     * Bind app to Component
     *
     * @param Handler $app
     * @return $this
     */
    public function bind(Handler &$app)
    {
        $this->app = $app;
        return $this;
    }

    /**
     * Setup component
     */
    public function setup() {}

    /**
     * Handle request/response
     *
     * @param Request $request
     * @param Response $response
     * @param callable $process
     * @return Response
     */
    abstract public function handle(Request $request, Response $response, callable $process = null);

}