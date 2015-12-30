<?php

namespace Colorium\Kernel;

class Api extends Handler
{

    /**
     * Api constructor
     */
    public function __construct()
    {
        parent::__construct(
            new Component\Catching,
            new Component\Routing,
            new Component\Resolving,
            new Component\Injecting,
            new Component\Jsoning
        );
    }

}