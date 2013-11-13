<?php

namespace Ciconia\Diagnose;

use Ciconia\Markdown as BaseMarkdown;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class Markdown extends BaseMarkdown
{

    private static $depth = 0;

    /**
     * @var Stopwatch
     */
    private $stopwatch;

    /**
     * @var array
     */
    private $events = [];

    /**
     *
     */
    public function start()
    {
        $this->stopwatch = new Stopwatch();
    }

    /**
     * @return Event[]
     */
    public function stop()
    {
        return $this->events;
    }

    /**
     * @param $event
     * @param $parameters
     *
     * @return mixed|void
     */
    public function emit($event, $parameters)
    {
        self::$depth++;

        $this->stopwatch->openSection();

        if (isset($this->callbacks[$event])) {
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
                $name = $this->getCallableName($item[1]);
                $this->stopwatch->start($name);

                $diagnoseEvent = Event::create()
                    ->setEvent($event)
                    ->setCallback($name)
                    ->setDepth(self::$depth);

                $this->events[] = $diagnoseEvent;

                call_user_func_array($item[1], $this->buildParameters($parameters));
                $stopwatchEvent = $this->stopwatch->stop($name);

                $diagnoseEvent
                    ->setDuration($stopwatchEvent->getDuration())
                    ->setMemory($stopwatchEvent->getMemory());
            }
        }

        $this->stopwatch->stopSection($event);

        self::$depth--;
    }

    /**
     * @param $callable
     *
     * @return string
     */
    public function getCallableName($callable)
    {
        $name = '(Unknown)';

        if (is_array($callable)) {
            $method = new \ReflectionMethod($callable[0], $callable[1]);
            $className = $method->getDeclaringClass()->getName();
            $className = str_replace('Ciconia\\Extension\\', '', $className);

            $name = $className . ':' . $method->getShortName();
        }

        return $name;
    }

} 