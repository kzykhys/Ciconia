<?php

namespace Ciconia\Extension\Core;

use Ciconia\Common\Text;
use Ciconia\Extension\ExtensionInterface;
use Ciconia\Renderer\RendererAwareInterface;
use Ciconia\Renderer\RendererAwareTrait;
use Ciconia\Markdown;

/**
 * Converts block and line code
 *
 * Original source code from Markdown.pl
 *
 * > Copyright (c) 2004 John Gruber
 * > <http://daringfireball.net/projects/markdown/>
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class CodeExtension implements ExtensionInterface, RendererAwareInterface
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

        $markdown->on('block', array($this, 'processCodeBlock'), 40);
        $markdown->on('inline', array($this, 'processCodeSpan'), 10);
    }

    /**
     * @param Text  $text
     * @param array $options
     */
    public function processCodeBlock(Text $text, array $options = array())
    {
        /** @noinspection PhpUnusedParameterInspection */
        $text->replace('{
            (?:\n\n|\A)
            (                                               # $1 = the code block -- one or more lines, starting with a space/tab
              (?:
                (?:[ ]{' . $options['tabWidth'] . '} | \t)  # Lines must start with a tab or a tab-width of spaces
                .*\n+
              )+
            )
            (?:(?=^[ ]{0,' . $options['tabWidth'] . '}\S)|\Z) # Lookahead for non-space at line-start, or end of doc
        }mx', function (Text $whole, Text $code) {
            $this->markdown->emit('outdent', array($code));
            $code->escapeHtml(ENT_NOQUOTES);
            $this->markdown->emit('detab', array($code));
            $code->replace('/\A\n+/', '');
            $code->replace('/\s+\z/', '');

            return "\n\n" . $this->getRenderer()->renderCodeBlock($code) . "\n\n";
        });
    }

    /**
     * @param Text $text
     */
    public function processCodeSpan(Text $text)
    {
        if (!$text->contains('`')) {
            return;
        }

        $chars = ['\\\\', '`', '\*', '_', '{', '}', '\[', '\]', '\(', '\)', '>', '#', '\+', '\-', '\.', '!'];
        $chars = implode('|', $chars);

        /** @noinspection PhpUnusedParameterInspection */
        $text->replace('{
            (`+)        # $1 = Opening run of `
            (.+?)       # $2 = The code block
            (?<!`)
            \1          # Matching closer
            (?!`)
        }x', function (Text $w, Text $b, Text $code) use ($chars) {
            $code->trim()->escapeHtml(ENT_NOQUOTES);
            $code->replace(sprintf('/(?<!\\\\)(%s)/', $chars), '\\\\${1}');

            return $this->getRenderer()->renderCodeSpan($code);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'code';
    }

}
