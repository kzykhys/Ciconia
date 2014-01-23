<?php

namespace Ciconia\Extension\Core;

use Ciconia\Common\Text;
use Ciconia\Event\EmitterAwareInterface;
use Ciconia\Event\EmitterAwareTrait;
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
class InlineStyleExtension implements ExtensionInterface, RendererAwareInterface, EmitterAwareInterface
{

    use RendererAwareTrait;
    use EmitterAwareTrait;

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
        $text->replace('{ ([^\*_\s]?) (\*\*|__) (?=\S) (.+?[*_]*) (?<=\S) \2 ([^\*_\s]?) }sx', function (Text $w, Text $prevChar, Text $a, Text $target, Text $nextChar) {
            if (!$prevChar->isEmpty() && !$nextChar->isEmpty() && $target->contains(' ')) {
                $this->getEmitter()->emit('escape.special_chars', [$w->replaceString(['*', '_'], ['\\*', '\\_'])]);

                return $w;
            }

            return $prevChar . $this->getRenderer()->renderBoldText($target) . $nextChar;
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
        $text->replace('{ ([^\*_\s]?) (\*|_) (?=\S) (.+?) (?<=\S) \2 ([^\*_\s]?) }sx', function (Text $w, Text $prevChar, Text $a, Text $target, Text $nextChar) {
            if (!$prevChar->isEmpty() && !$nextChar->isEmpty() && $target->contains(' ')) {
                $this->getEmitter()->emit('escape.special_chars', [$w->replaceString(['*', '_'], ['\\*', '\\_'])]);

                return $w;
            }

            return $prevChar . $this->getRenderer()->renderItalicText($target) . $nextChar;
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