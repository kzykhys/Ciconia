<?php

namespace Ciconia\Extension\Textile;

use Ciconia\Common\Text;
use Ciconia\Extension\ExtensionInterface;
use Ciconia\Markdown;

class CommentExtension implements ExtensionInterface
{

    /**
     * {@inheritdoc}
     */
    public function register(Markdown $markdown)
    {
        $markdown->on('block', array($this, 'processComment'), 50);
    }

    /**
     * @param Text $text
     */
    public function processComment(Text $text)
    {
        $text->replace('/^###\.[ \t]*(.+?)\n{2,}/m', "\n\n");
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'comment';
    }
}