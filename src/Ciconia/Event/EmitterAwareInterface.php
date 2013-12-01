<?php

namespace Ciconia\Event;

/**
 * EmitterAwareInterface should be implemented by classes that depends on EmitterInterface
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
interface EmitterAwareInterface
{

    /**
     * @api
     *
     * @param EmitterInterface $emitter
     *
     * @return mixed
     */
    public function setEmitter(EmitterInterface $emitter);

    /**
     * @api
     *
     * @return EmitterInterface
     */
    public function getEmitter();

}