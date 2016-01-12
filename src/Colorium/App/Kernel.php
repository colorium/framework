<?php

namespace Colorium\App;

use Colorium\Http;

class Kernel extends \stdClass implements Handler
{

    /** @var Context */
    public $context;

    /** @var callable[] */
    protected $plugins = [];


    /**
     * Kernel constructor
     *
     * @param Plugin ...$plugins
     */
    public function __construct(Plugin ...$plugins)
    {
        $this->context = new Context;
        $this->context->request = Http\Request::current();
        $this->context->response = new Http\Response;

        $plugins[] = new Kernel\Execution;
        $plugins = array_reverse($plugins);
        foreach($plugins as $plugin) {
            $this->plug($plugin);
        }
    }


    /**
     * Attach plugin
     *
     * @param Plugin $plugin
     */
    protected function plug(Plugin $plugin)
    {
        $chain = reset($this->plugins) ?: null;
        $plugin->bind($this)->setup();
        $callable = function(Context $context) use($plugin, $chain) {
            return $plugin->handle($context, $chain);
        };
        array_unshift($this->plugins, $callable);
    }


    /**
     * Handle app context
     *
     * @param Context $context
     * @return Context
     */
    public function handle(Context $context)
    {
        $chain = reset($this->plugins);
        $context = call_user_func($chain, $context);

        return $context;
    }


    /**
     * Run kernel
     *
     * @param Context $context
     * @return Context
     */
    public function run(Context $context = null)
    {
        $context = $context ?: $this->context;
        $context = $this->handle($context);
        $context->response->send();

        return $context;
    }


    /**
     * Forward directly to invokable
     *
     * @param $resource
     * @param ...$params
     * @return Context
     */
    public function forward($resource, ...$params)
    {
        $context = clone $this->context;
        $context->forward = [$resource, $params];
        $context->invokable = null;

        return $this->run($context);
    }

}