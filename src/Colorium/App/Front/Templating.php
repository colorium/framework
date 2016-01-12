<?php

namespace Colorium\App\Front;

use Colorium\App\Context;
use Colorium\App\Plugin;
use Colorium\Http\Request;
use Colorium\Http\Response;
use Colorium\Http\Error;
use Colorium\Templating\Engine;
use Colorium\Templating\Html;

class Templating extends Plugin
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
     * @param Context $context
     * @param callable $chain
     * @return Response
     */
    public function handle(Context $context, callable $chain = null)
    {
        $context = $chain($context);

        // add template uri helper
        $this->templater->helpers['url'] = function(...$parts) use($context)
        {
            $path = ltrim(implode('/', $parts));
            return $context->request->uri->make($path);
        };

        // expect valid response
        if(!$context->response instanceof Response) {
            throw new \RuntimeException('Context::response must be a valid Colorium\Http\Response instance');
        }
        // render template
        elseif($context->response instanceof Response\Template) {
            $context->response->content = $this->templater->render($context->response->template, $context->response->vars);
        }
        // render redirect
        elseif($context->response instanceof Response\Redirect and $context->response->uri[0] == '/') {
            $context->response->uri = $context->request->uri->make($context->response->uri);
        }
        // render default as template or json
        elseif($context->response->raw) {
            // render template
            if($template = $context->invokable->annotation('html')) {
                $content = $this->templater->render($template, (array)$context->response->content);
                $context->response = new Response\Html($content, $context->response->code);
            }
            // render json
            else {
                $context->response = new Response\Json($context->response->content, $context->response->code);
            }
        }

        return $context;
    }

}