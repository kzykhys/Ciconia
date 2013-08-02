<?php

namespace Ciconia\Extension\Core;

use Ciconia\Common\Text;
use Ciconia\Extension\ExtensionInterface;
use Ciconia\Markdown;

/**
 * Hashes HTML text
 *
 * Original source code from Markdown.pl and PHP Markdown
 *
 * > Copyright (c) 2004 John Gruber
 * > <http://daringfireball.net/projects/markdown/>
 *
 * > PHP Markdown Lib Copyright (c) 2004-2013 Michel Fortin
 * > <http://michelf.ca/>
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class HtmlBlockExtension implements ExtensionInterface
{

    /**
     * @var Markdown
     */
    private $markdown;

    /**
     * @var string
     */
    private $tagsA = '/p|div|h[1-6]|blockquote|pre|table|dl|ol|ul|script|noscript|form|fieldset|iframe|math|ins|del/';

    /**
     * @var string
     */
    private $tagsB = '/p|div|h[1-6]|blockquote|pre|table|dl|ol|ul|script|noscript|form|fieldset|iframe|math/';

    /**
     * {@inheritdoc}
     */
    public function register(Markdown $markdown)
    {
        $this->markdown = $markdown;

        $markdown->on('initialize', array($this, 'hashHtmlBlocks'), 20);
        $markdown->on('block', array($this, 'hashHtmlBlocks'), 100);
        $markdown->on('finalize', array($this, 'unhashHtmlBlocks'), 10);
    }

    /**
     * @param Text  $text
     * @param array $options
     */
    public function hashHtmlBlocks(Text $text, array $options = array())
    {
        $lessThanTab = $options['tabWidth'] - 1;

        /*
         * Original source code from PHP Markdown
         *
         * > PHP Markdown Lib Copyright (c) 2004-2013 Michel Fortin
         * > <http://michelf.ca/>
         */
        $nestedHtml =
            str_repeat('(?>[^<]+|<\2.*?(?>/>|>', $options['nestedTagLevel']) .
            '.*?' .
            str_repeat('</\2\s*>)|<(?!/\2\s*>))*', $options['nestedTagLevel']);

        /** @noinspection PhpUnusedParameterInspection */
        $callback = function ($whole, $html, $tag) {
            $hash = $this->markdown->getHashRegistry()->register($html);

            return "\n\n" . $hash . "\n\n";
        };

        $text->replace('{
            (                           # save in $1
                ^                       # start of line  (with /m)
                <(' . $this->tagsA . ') # start tag = $2
                #\b                     # word break
                .*?>' . $nestedHtml . ' # any number of lines, **NOT** minimally matching
                </\2>                   # the matching end tag
                [ \t]*                  # trailing spaces/tabs
                (?=\n+|\Z)              # followed by a newline or end of document
            )
        }mx', $callback);

        $text->replace('{
            (                           # save in $1
                ^                       # start of line  (with /m)
                <(' . $this->tagsB . ') # start tag = $2
                \b                      # word break
                (.*\n)*?                # any number of lines,  **NOT** minimally matching
                .*</\2>                 # the matching end tag
                [ \t]*                  # trailing spaces/tabs
                (?=\n+|\Z)              # followed by a newline or end of document
            )
        }mx', $callback);

        $text->replace('{
            (?:
                (?<=\n\n)               # Starting after a blank line
                |                       # or
                \A\n?                   # the beginning of the doc
            )
            (                           # save in $1
                [ ]{0,' . $lessThanTab . '}
                <(hr)                   # start tag = $2
                \b                      # word break
                ([^<>])*?               #
                /?>                     # the matching end tag
                [ \t]*
                (?=\n{2,}|\Z)           # followed by a blank line or end of document
            )
        }x', $callback);

        $text->replace('{
            (?:
                (?<=\n\n)       # Starting after a blank line
                |               # or
                \A\n?           # the beginning of the doc
            )
            (                   # save in $1
                [ ]{0,' . $lessThanTab . '}
                (?s:
                    <!
                    (--.*?--\s*)+
                    >
                )
                [ \t]*
                (?=\n{2,}|\Z)   # followed by a blank line or end of document
            )
        }x', $callback);
    }

    /**
     * @param Text $text
     */
    public function unhashHtmlBlocks(Text $text)
    {
        foreach ($this->markdown->getHashRegistry() as $hash => $html) {
            $text->replaceString($hash, trim($html));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'htmlBlock';
    }

}
