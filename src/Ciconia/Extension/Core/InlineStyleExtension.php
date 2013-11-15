<?php

namespace Ciconia\Extension\Core;

use Ciconia\Common\Text;
use Ciconia\Extension\ExtensionInterface;
use Ciconia\Renderer\RendererAwareInterface;
use Ciconia\Renderer\RendererAwareTrait;
use Ciconia\Markdown;

/**
 * Original source code from Markdown.pl
 *
 * > Copyright (c) 2004 John Gruber
 * > <http://daringfireball.net/projects/markdown/>
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class InlineStyleExtension implements ExtensionInterface, RendererAwareInterface
{

    use RendererAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function register(Markdown $markdown)
    {
        $markdown->on('inline', array($this, 'processBold'), 70);
        $markdown->on('inline', array($this, 'processItalic'), 71);
    }

    /**
     * @param Text $text
     */
    public function processBold(Text $text)
    {
        if (!$text->contains('**') && !$text->contains('__')) {
            return;
        }

        /** @noinspection PhpUnusedParameterInspection */
        $text->replace('{ (\*\*|__) (?=\S) (.+?[*_]*) (?<=\S) \1 }sx', function (Text $w, Text $a, Text $target) {
            return $this->getRenderer()->renderBoldText($target);
        });
    }

    /**
     * @param Text $text
     */
    public function processItalic(Text $text)
    {
        if (!$text->contains('*') && !$text->contains('_')) {
            return;
        }

        /** @noinspection PhpUnusedParameterInspection */
        $text->replace('{ (\*|_) (?=\S) (.+?) (?<=\S) \1 }sx', function (Text $w, Text $a, Text $target) {
            return $this->getRenderer()->renderItalicText($target);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'inlineStyle';
    }

}