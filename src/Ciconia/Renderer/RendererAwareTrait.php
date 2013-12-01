<?php

namespace Ciconia\Renderer;

/**
 * Implementation of RendererAwareInterface
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
trait RendererAwareTrait
{

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @return RendererInterface
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param RendererInterface $renderer
     */
    public function setRenderer(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

}