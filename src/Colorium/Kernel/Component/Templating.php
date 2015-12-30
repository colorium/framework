<?php

namespace Colorium\Kernel\Component;

use Colorium\Runtime\Resource;
use Colorium\Kernel\Component;
use Colorium\Http\Request;
use Colorium\Http\Response;
use Colorium\Http\Error;
use Colorium\View\Engine;
use Colorium\View\Html;

class Templating extends Component
{

    /** @var Engine */
    protected $templater;


    /**
     * Create templating component
     *
     * @param Engine $templater
     */
    public function __construct(Engine $templater = null)
    {
        $this->templater = $templater ?: new Html;
    }


    /**
     * Setup templater
     */
    public function setup()
    {
        $this->app->templater = &$this->templater;
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
     * @throws Error\NotImplemented
     */
    public function handle(Request $request, Response $response, callable $process)
    {
        $response = $process($request, $response);

        // render raw content
        if($response->raw) {

            // render template if resource resolved
            if($request->context->resource instanceof Resource and $template = $request->context->resource->annotation('html')) {
                $content = $this->templater->render($template, (array)$response->content);
                return new Response\Html($content, $response->code);
            }

            // default: transform to json
            return new Response\Json($response->content, $response->code);
        }
        // template response
        elseif($response instanceof Response\Template) {
            $response->content = $this->templater->render($response->template, $response->vars);
        }

        return $response;
    }

}