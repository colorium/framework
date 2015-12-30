<?php

namespace Colorium\Routing;

interface Routable
{

    /**
     * Get all routes
     *
     * @return Route[]
     */
    public function routes();

    /**
     * Find route from query
     *
     * @param string $query
     * @return Route
     */
    public function find($query);

    /**
     * Find route from target
     *
     * @param $resource
     * @param array $params
     * @return Route
     */
    public function reverse($resource, array $params = []);

}