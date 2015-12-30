<?php

namespace Colorium\Kernel;

class App extends Handler
{

    /**
     * App constructor
     */
    public function __construct()
    {
        parent::__construct(
            new Component\Catching,
            new Component\Routing,
            new Component\Resolving,
            new Component\Authenticating,
            new Component\Injecting,
            new Component\Templating
        );
    }

}