<?php

namespace Colorium\Event;

class Store implements Subject
{

    /** @var callable[] */
    protected $events = [];


    /**
     * Attache listener
     *
     * @param string $event
     * @param callable $listener
     * @return $this
     */
    public function on($event, callable $listener)
    {
        $this->events[$event] = $listener;
        return $this;
    }


    /**
     * Trigger event
     *
     * @param string $event
     * @param array $params
     * @param callable $wrapper
     * @return mixed
     */
    public function fire($event, array $params = [], callable $wrapper = null)
    {
        if(isset($this->events[$event])) {
            $listener = $this->events[$event];
            return $wrapper
                ? $wrapper($listener, ...$params)
                : $listener(...$params);
        }
    }

}