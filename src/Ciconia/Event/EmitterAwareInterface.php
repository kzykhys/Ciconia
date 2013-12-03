<?php

namespace Ciconia\Event;

use
    Sabre\Event\EventEmitterInterface;

/**
 * EmitterAwareInterface should be implemented by classes that depends on
 * Sabre\Event\EventEmitterInterface.
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
interface EmitterAwareInterface
{

    /**
     * @api
     *
     * @param EventEmitterInterface $emitter
     *
     * @return mixed
     */
    public function setEmitter(EventEmitterInterface $emitter);

    /**
     * @api
     *
     * @return EventEmitterInterface
     */
    public function getEmitter();

}
