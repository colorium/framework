<?php

namespace Colorium\Kernel\Component\Required;

use Colorium\Kernel\Component;
use Colorium\Http\Request;
use Colorium\Http\Response;
use Colorium\Http\Error;
use Colorium\Runtime\Resolver;

class Executing extends Component
{


    /**
     * Handle request/response
     *
     * @param Request $request
     * @param Response $response
     * @param callable $process
     * @return Response
     *
     * @throws Error\NotImplemented
     */
    public function handle(Request $request, Response $response, callable $process = null)
    {
        // expect valid resource
        if(!is_callable($request->context->resource)) {
            throw new \RuntimeException('$request->context->resource must be a valid callable');
        }

        // instanciate resource callable
        if($request->context->resource instanceof Resolver\Resource) {
            $request->context->resource->instanciate();
        }

        // execute resource
        $result = call_user_func_array($request->context->resource, (array)$request->context->params);
        if($result instanceof Response) {
            return $result;
        }

        $response->raw = true;
        $response->content = $result;
        return $response;
    }

}