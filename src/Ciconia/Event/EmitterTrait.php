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
    protected $callbacks = array();

    /**
     * Adds a listener to the end of the listeners array for the specified event.
     *
     * @param string   $event    The name of the event
     * @param callable $callback The callback
     * @param int      $priority The lower this value, the earlier an event listener will be emitted.
     *
     * @return EmitterInterface
     */
    public function on($event, callable $callback, $priority = 10)
    {
        if (!isset($this->callbacks[$event])) {
            $this->callbacks[$event] = [true, []];
        }

        $this->callbacks[$event][0] = false;
        $this->callbacks[$event][1][] = array($priority, $callback);

        return $this;
    }

    /**
     * Execute each of the subscribed listeners
     *
     * @param string $event      The name of the event to emit
     * @param array  $parameters The arguments
     */
    public function emit($event, $parameters)
    {
        if (!isset($this->callbacks[$event])) {
            return;
        }

        if (!$this->callbacks[$event][0]) {
            usort($this->callbacks[$event][1], function ($A, $B) {
                if ($A[0] == $B[0]) {
                    return 0;
                }

                return ($A[0] > $B[0]) ? 1 : -1;
            });

            $this->callbacks[$event][0] = true;
        }

        foreach ($this->callbacks[$event][1] as $item) {
            call_user_func_array($item[1], $this->buildParameters($parameters));
        }
    }

    /**
     * Build parameters to pass to the listeners
     *
     * @codeCoverageIgnore
     *
     * @param array $parameters The arguments to pass to the callback
     *
     * @return array
     */
    protected function buildParameters(array $parameters = array()) {
        return $parameters;
    }

}