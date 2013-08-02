<?php

namespace Ciconia\Extension\Core;

use Ciconia\Common\Text;
use Ciconia\Extension\ExtensionInterface;
use Ciconia\Renderer\RendererAwareInterface;
use Ciconia\Renderer\RendererAwareTrait;
use Ciconia\Markdown;

/**
 * Converts horizontal rules
 *
 * Original source code from Markdown.pl
 *
 * > Copyright (c) 2004 John Gruber
 * > <http://daringfireball.net/projects/markdown/>
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class HorizontalRuleExtension implements ExtensionInterface, RendererAwareInterface
{

    use RendererAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function register(Markdown $markdown)
    {
        $markdown->on('block', array($this, 'processHorizontalRule'), 20);
    }

    /**
     * @param Text  $text
     */
    public function processHorizontalRule(Text $text)
    {
        $marks = array('\*', '-', '_');

        foreach ($marks as $mark) {
            $text->replace(
                '/^[ ]{0,2}([ ]?' . $mark . '[ ]?){3,}[ \t]*$/m',
                $this->getRenderer()->renderHorizontalRule() . "\n\n"
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'hr';
    }

}