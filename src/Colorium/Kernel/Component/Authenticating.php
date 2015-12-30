<?php

namespace Colorium\Kernel\Component;

use Colorium\Http;
use Colorium\Runtime\Resource;
use Colorium\Kernel\Component;

class Authenticating extends Component
{

    /**
     * Basic auth Component
     *
     * @param Http\Request $request
     * @param Http\Response $response
     * @param callable $process
     * @return Http\Response
     *
     * @throws Http\Error\Unauthorized
     */
    public function handle(Http\Request $request, Http\Response $response, callable $process)
    {
        // need resource resolving
        if($request->context->resource instanceof Resource) {
            $rank = $request->context->resource->annotation('access') ?: 0;
            if($rank and Http\Session::rank() < $rank) {
                throw new Http\Error\Unauthorized;
            }
        }

        // update context
        $request->context->user = Http\Session::user();

        return $process($request, $response);
    }

}