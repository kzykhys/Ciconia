<?php

namespace Ciconia\Event;

/**
 * Central event manager (heavily inspired by Node.js's EventEmitter)
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
interface EmitterInterface
{

    /**
     * Adds a listener to the end of the listeners array for the specified event.
     *
     * @param string   $event
     * @param callable $callback
     * @param int      $priority
     *
     * @return mixed
     */
    public function on($event, callable $callback, $priority = 10);

    /**
     * Execute each of the subscribed listeners
     *
     * @param $event
     * @param $parameters
     *
     * @return mixed
     */
    public function emit($event, $parameters);

}