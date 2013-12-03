<?php

namespace Ciconia\Event;

use
    Sabre\Event\EventEmitterInterface;

/**
 * Implementation of EmitterAwareInterface
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
trait EmitterAwareTrait
{

    /**
     * @var EventEmitterInterface
     */
    private $emitter;

    /**
     * @param EventEmitterInterface $emitter
     */
    public function setEmitter(EventEmitterInterface $emitter)
    {
        $this->emitter = $emitter;
    }

    /**
     * @return EventEmitterInterface
     */
    public function getEmitter()
    {
        return $this->emitter;
    }

}
