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
     * @param EmitterInterface $emitter
     *
     * @return mixed
     */
    public function setEmitter(EmitterInterface $emitter);

    /**
     * @return EmitterInterface
     */
    public function getEmitter();

}