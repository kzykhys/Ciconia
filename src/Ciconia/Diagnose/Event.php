<?php

namespace Ciconia\Diagnose;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class Event
{

    /**
     * @var string
     */
    private $event;

    /**
     * @var string
     */
    private $callback;

    /**
     * @var int
     */
    private $duration;

    /**
     * @var int
     */
    private $memory;

    /**
     * @var int
     */
    private $depth;

    /**
     * @return Event
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param string $callback
     *
     * @return $this
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * @return string
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param int $depth
     *
     * @return $this
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @param int $duration
     *
     * @return $this
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param string $event
     *
     * @return $this
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param int $memory
     *
     * @return $this
     */
    public function setMemory($memory)
    {
        $this->memory = $memory;

        return $this;
    }

    /**
     * @return int
     */
    public function getMemory()
    {
        return $this->memory;
    }

} 