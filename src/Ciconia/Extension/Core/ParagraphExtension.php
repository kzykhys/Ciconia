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
class ParagraphExtension implements ExtensionInterface, RendererAwareInterface
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

        $markdown->on('block', array($this, 'buildParagraph'), 120);
    }

    /**
     * @param Text $text
     */
    public function buildParagraph(Text $text)
    {
        $parts = $text
            ->replace('/\A\n+/', '')
            ->replace('/\n+\z/', '')
            //->replace('/\n+$/', '')
            ->split('/\n{2,}/', PREG_SPLIT_NO_EMPTY);

        $parts->apply(function (Text $part) {
            if (!$this->markdown->getHashRegistry()->exists($part)) {
                $this->markdown->emit('inline', array($part));
                $part->replace('/^([ \t]*)/', '');

                $part->setString($this->getRenderer()->renderParagraph((string) $part));
            }

            return $part;
        });

        $parts->apply(function (Text $part) {
            if ($this->markdown->getHashRegistry()->exists($part)) {
                $part->setString(trim($this->markdown->getHashRegistry()->get($part)));
            }

            return $part;
        });

        $text->setString($parts->join("\n\n"));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'paragraph';
    }

}