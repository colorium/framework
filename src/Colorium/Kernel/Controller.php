<?php

namespace Colorium\Kernel;

use Colorium\Http\Response;
use Colorium\Http\Session;

trait Controller
{

    /**
     * Create json response
     *
     * @param string $content
     * @param int $code
     * @param array $headers
     * @return Response\Json
     */
    protected static function json($content, $code = 200, array $headers = [])
    {
        return new Response\Json($content, $code, $headers);
    }


    /**
     * Create redirect response
     *
     * @param string $url
     * @param int $code
     * @param array $headers
     * @return Response\Redirect
     */
    protected static function http_redirect($url, $code = 302, array $headers = [])
    {
        return new Response\Redirect($url, $code, $headers);
    }


    /**
     * Create template response
     *
     * @param $template
     * @param array $vars
     * @param int $code
     * @param array $headers
     * @return Response\Template
     */
    protected static function http_template($template, array $vars = [], $code = 200, array $headers = [])
    {
        return new Response\Template($template, $vars, $code, $headers);
    }


    /**
     * Read-only session
     *
     * @param string $key
     * @param mixed $fallback
     * @return mixed
     */
    protected static function session($key, $fallback = null)
    {
        return Session::get($key, $fallback);
    }


    /**
     * Read-only flash
     *
     * @param string $key
     * @return mixed
     */
    protected static function flash($key)
    {
        return Session::flash($key);
    }


}