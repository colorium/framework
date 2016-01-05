<?php

namespace Colorium\Kernel\Component;

use Colorium\Kernel\Component;
use Colorium\Http\Request;
use Colorium\Http\Response;
use Colorium\Http\Error;

class Jsoning extends Component
{


    /**
     * Handle request/response
     *
     * @param Request $request
     * @param Response $response
     * @param callable $process
     * @return Response
     *
     * @throws Error\NotFound
     * @throws Error\NotImplemented
     */
    public function handle(Request $request, Response $response, callable $process = null)
    {
        $response = $process($request, $response);

        if($response->raw) {
            return new Response\Json($response->content, $response->code);
        }

        return $response;
    }

}