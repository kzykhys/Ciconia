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
class WhitespaceExtension implements ExtensionInterface, RendererAwareInterface
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

        $markdown->on('initialize', array($this, 'initialize'), 10);
        $markdown->on('detab', array($this, 'detab'), 10);
        $markdown->on('outdent', array($this, 'outdent'), 10);
        $markdown->on('inline', array($this, 'processHardBreak'), 80);
    }

    /**
     * Convert line breaks
     *
     * @param Text $text
     */
    public function initialize(Text $text)
    {
        $text->replaceString("\r\n", "\n");
        $text->replaceString("\r", "\n");

        $text->append("\n\n");
        $this->markdown->emit('detab', array($text));
        $text->replace('/^[ \t]+$/m', '');
    }

    /**
     * Convert tabs to spaces
     *
     * @param Text  $text
     * @param array $options
     */
    public function detab(Text $text, array $options = array())
    {
        /** @noinspection PhpUnusedParameterInspection */
        $text->replace('/(.*?)\t/', function (Text $whole, Text $string) use ($options) {
            return $string . str_repeat(' ', $options['tabWidth'] - $string->getLength() % $options['tabWidth']);
        });
    }

    /**
     * Remove one level of line-leading tabs or spaces
     *
     * @param Text  $text
     * @param array $options
     */
    public function outdent(Text $text, array $options = array())
    {
        $text->replace('/^(\t|[ ]{1,' . $options['tabWidth'] . '})/m', '');
    }

    /**
     * @param Text  $text
     */
    public function processHardBreak(Text $text)
    {
        $text->replace('/ {2,}\n/', $this->getRenderer()->renderLineBreak() .  "\n");
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'whitespace';
    }

}