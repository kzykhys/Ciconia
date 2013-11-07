<?php

namespace Ciconia\Event;

/**
 * Implementation of EmitterAwareInterface
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
trait EmitterAwareTrait
{

    /**
     * @var EmitterInterface
     */
    private $emitter;

    /**
     * @param EmitterInterface $emitter
     */
    public function setEmitter(EmitterInterface $emitter)
    {
        $this->emitter = $emitter;
    }

    /**
     * @return EmitterInterface
     */
    public function getEmitter()
    {
        return $this->emitter;
    }

}