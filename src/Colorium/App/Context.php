<?php

namespace Colorium\App;

use Colorium\Http;
use Colorium\Routing;
use Colorium\Runtime;

class Context extends \stdClass
{

    /** @var Http\Request */
    public $request;

    /** @var Routing\Route */
    public $route;

    /** @var Runtime\Invokable */
    public $invokable;

    /** @var Http\Response */
    public $response;


    /**
     * Uri helper
     *
     * @param ...$parts
     * @return string
     */
    public function uri(...$parts)
    {
        $uri = implode('/', $parts);
        return (string)$this->request->uri->make($uri);
    }


    /**
     * Generate redirect response
     *
     * @param string $uri
     * @param int $code
     * @param array $headers
     * @return Http\Response\Redirect
     */
    public static function redirect($uri, $code = 302, array $headers = [])
    {
        return new Http\Response\Redirect($uri, $code, $headers);
    }


    /**
     * Generate json response
     *
     * @param $content
     * @param int $code
     * @param array $headers
     * @return Http\Response\Json
     */
    public static function json($content, $code = 302, array $headers = [])
    {
        return new Http\Response\Json($content, $code, $headers);
    }


    /**
     * Generate template response
     *
     * @param string $template
     * @param array $vars
     * @param int $code
     * @param array $headers
     * @return Http\Response\Template
     */
    public static function template($template, array $vars = [], $code = 200, array $headers = [])
    {
        return new Http\Response\Template($template, $vars, $code, $headers);
    }

}