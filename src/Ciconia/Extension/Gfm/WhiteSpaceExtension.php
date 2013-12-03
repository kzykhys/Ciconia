<?php

namespace Ciconia\Extension\Gfm;

use Ciconia\Common\Text;
use Ciconia\Extension\ExtensionInterface;
use Ciconia\Markdown;

/**
 * Original source code from GitHub Flavored Markdown
 *
 * > Copyright 2013 GitHub Inc.
 * > https://help.github.com/articles/github-flavored-markdown
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class WhiteSpaceExtension implements ExtensionInterface
{

    /**
     * {@inheritdoc}
     */
    public function register(Markdown $markdown)
    {
        $markdown->on('inline', array($this, 'processHardBreak'), 75);
    }

    /**
     * Newlines
     *
     * The biggest difference that GFM introduces is in the handling of line breaks.
     * With SM you can hard wrap paragraphs of text and they will be combined into a single paragraph.
     * We find this to be the cause of a huge number of unintentional formatting errors.
     * GFM treats newlines in paragraph-like content as real line breaks, which is probably what you intended.
     *
     * @param Text $text
     */
    public function processHardBreak(Text $text)
    {
        $text->replace('/^[\S\<][^\n]*\n+(?!( |\t)*<)/m', function (Text $w) {
            if ($w->match('/\n{2}/') || $w->match('/  \n/')) {
                return $w;
            }

            return $w->trim()->append("  \n");
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'gfmWhitespace';
    }

}