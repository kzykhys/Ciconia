<?php

namespace Ciconia\Renderer;

/**
 * Renders markdown result to XHTML format
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class XhtmlRenderer extends HtmlRenderer
{

    /**
     * {@inheritdoc}
     */
    protected function getEmptyTagSuffix()
    {
        return ' />';
    }

}