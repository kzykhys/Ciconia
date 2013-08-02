<?php

namespace Ciconia\Renderer;

use Ciconia\Renderer\RendererInterface;

/**
 * RendererAwareInterface should be implemented by extension classes that depends on RendererInterface
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
interface RendererAwareInterface
{

    /**
     * @return RendererInterface
     */
    public function getRenderer();

    /**
     * @param RendererInterface $renderer
     */
    public function setRenderer(RendererInterface $renderer);

}