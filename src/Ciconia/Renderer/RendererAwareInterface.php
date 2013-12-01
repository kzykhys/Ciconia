<?php

namespace Ciconia\Renderer;

/**
 * RendererAwareInterface should be implemented by extension classes that depends on RendererInterface
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
interface RendererAwareInterface
{

    /**
     * @api
     *
     * @return RendererInterface
     */
    public function getRenderer();

    /**
     * @api
     *
     * @param RendererInterface $renderer
     */
    public function setRenderer(RendererInterface $renderer);

}