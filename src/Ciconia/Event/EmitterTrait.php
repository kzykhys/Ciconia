<?php

namespace Ciconia\Event;

/**
 * Central event manager (heavily inspired by Node.js's EventEmitter)
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
trait EmitterTrait
{

    /**
     * @var array
     */
    private $callbacks = array();

    /**
     * Adds a listener to the end of the listeners array for the specified event.
     *
     * @param string   $event
     * @param callable $callback
     * @param int      $priority
     *
     * @return $this
     */
    public function on($event, callable $callback, $priority = 10)
    {
        if (!isset($this->callbacks[$event])) {
            $this->callbacks[$event] = array();
        }

        $this->callbacks[$event][] = array($priority, $callback);

        return $this;
    }

    /**
     * Execute each of the subscribed listeners
     *
     * @param $event
     * @param $parameters
     *
     * @return mixed
     */
    public function emit($event, $parameters)
    {
        if (isset($this->callbacks[$event])) {
            usort($this->callbacks[$event], function ($A, $B) {
                if ($A[0] == $B[0]) {
                    return 0;
                }

                return ($A[0] > $B[0]) ? 1 : -1;
            });

            foreach ($this->callbacks[$event] as $item) {
                call_user_func_array($item[1], $this->buildParameters($parameters));
            }
        }
    }

    /**
     * Build parameters to pass to the listeners
     *
     * @codeCoverageIgnore
     *
     * @param array $parameters
     *
     * @return array
     */
    protected function buildParameters(array $parameters = array()) {
        return $parameters;
    }

}