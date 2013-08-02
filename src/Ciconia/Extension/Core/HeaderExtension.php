<?php

namespace Ciconia\Extension\Core;

use Ciconia\Common\Text;
use Ciconia\Extension\ExtensionInterface;
use Ciconia\Renderer\RendererAwareInterface;
use Ciconia\Renderer\RendererAwareTrait;
use Ciconia\Markdown;

/**
 * Converts text to <blockquote>
 *
 * Original source code from Markdown.pl
 *
 * > Copyright (c) 2004 John Gruber
 * > <http://daringfireball.net/projects/markdown/>
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class HeaderExtension implements ExtensionInterface, RendererAwareInterface
{

    use RendererAwareTrait;

    /**
     * @var Markdown
     */
    private $markdown;

    /**
     * {@inheritdoc}
     */
    public function register(Markdown $markdown)
    {
        $this->markdown = $markdown;

        $markdown->on('block', array($this, 'processSetExtHeader'), 10);
        $markdown->on('block', array($this, 'processAtxHeader'), 11);
    }

    /**
     * @param Text $text
     */
    public function processSetExtHeader(Text $text)
    {
        /** @noinspection PhpUnusedParameterInspection */
        $text->replace('{^(.+)[ \t]*\n(=+|-+)[ \t]*\n+}m', function (Text $whole, Text $content, Text $mark) {
            $level = (substr($mark, 0, 1) == '=') ? 1 : 2;

            $this->markdown->emit('inline', array($content));

            return $this->getRenderer()->renderHeader($content, array('level' => $level)) . "\n\n";
        });
    }

    /**
     * @param Text $text
     */
    public function processAtxHeader(Text $text)
    {
        /** @noinspection PhpUnusedParameterInspection */
        $text->replace('{
            ^(\#{1,6})  # $1 = string of #\'s
            [ \t]*
            (.+?)       # $2 = Header text
            [ \t]*
            \#*         # optional closing #\'s (not counted)
            \n+
        }mx', function (Text $whole, Text $marks, Text $content) {
            $level = strlen($marks);

            $this->markdown->emit('inline', array($content));

            return $this->getRenderer()->renderHeader($content, array('level' => $level)) . "\n\n";
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'header';
    }

}
