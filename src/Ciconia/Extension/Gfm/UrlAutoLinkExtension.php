<?php

namespace Ciconia\Extension\Gfm;

use Ciconia\Common\Text;
use Ciconia\Extension\ExtensionInterface;
use Ciconia\Markdown;

/**
 * Turn standard URL into markdown URL (http://example.com -> <http://example.com>)
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class UrlAutoLinkExtension implements ExtensionInterface
{

    /**
     * {@inheritdoc}
     */
    public function register(Markdown $markdown)
    {
        $markdown->on('inline', array($this, 'processStandardUrl'), 35);
    }

    /**
     * Turn standard URL into markdown URL
     *
     * @param Text $text
     */
    public function processStandardUrl(Text $text)
    {
        $text->replace('{(?<!]\(|"|<|\[)((?:https?|ftp)://[^\'">\s]+)(?!>|\"|\])}', '<\1>');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'urlAutoLink';
    }

}