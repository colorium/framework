<?php

namespace Colorium\Event;

interface Subject
{

    /**
     * Attache listener
     *
     * @param string $event
     * @param callable $listener
     * @return $this
     */
    public function on($event, callable $listener);

    /**
     * Trigger event
     *
     * @param string $event
     * @param array $params
     * @param callable $wrapper
     * @return mixed
     */
    public function fire($event, array $params = [], callable $wrapper = null);

}