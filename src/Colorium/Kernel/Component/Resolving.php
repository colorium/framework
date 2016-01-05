<?php

namespace Colorium\Kernel\Component;

use Colorium\Kernel\Component;
use Colorium\Http\Request;
use Colorium\Http\Response;
use Colorium\Http\Error;
use Colorium\Runtime\Resolver;

class Resolving extends Component
{

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
        // skip resolving is the resource is already resolved as a runtime resource
        if(!$request->context->resource instanceof Resolver\Resource) {
            $resource = Resolver::of($request->context->resource);
            if(!$resource) {
                throw new \RuntimeException('Resource is not a valid resolvable resource');
            }

            $request->context->resource = $resource;
        }

        return $process($request, $response);
    }

}