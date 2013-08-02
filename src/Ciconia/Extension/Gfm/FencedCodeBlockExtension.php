<?php

namespace Ciconia\Extension\Gfm;

use Ciconia\Common\Text;
use Ciconia\Extension\ExtensionInterface;
use Ciconia\Renderer\RendererAwareInterface;
use Ciconia\Renderer\RendererAwareTrait;
use Ciconia\Markdown;

/**
 * Markdown converts text with four spaces at the front of each line to code blocks.
 * GFM supports that, but we also support fenced blocks.
 * Just wrap your code blocks in ``` and you won't need to indent manually to trigger a code block.
 *
 * PHP Markdown style `~` is also available.
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class FencedCodeBlockExtension implements ExtensionInterface, RendererAwareInterface
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

        // should be run before first hashHtmlBlocks
        $markdown->on('initialize', array($this, 'processFencedCodeBlock'));
    }

    /**
     * @param Text $text
     */
    public function processFencedCodeBlock(Text $text)
    {
        /** @noinspection PhpUnusedParameterInspection */
        $text->replace('{
            (?:\n\n|\A)
            (?:
                ([`~]{3})[ ]*         #1 fence ` or ~
                    ([a-zA-Z0-9]*?)?  #2 language [optional]
                \n+
                (.*?)\n                #3 code block
                \1                    # matched #1
            )
        }smx', function (Text $w, Text $fence, Text $lang, Text $code) {
            $options = array();

            if (!$lang->isEmpty()) {
                $options = array(
                    'attr' => array(
                        'class' => 'prettyprint lang-' . $lang->lower()
                    )
                );
            }

            $code->escapeHtml(ENT_NOQUOTES);
            $this->markdown->emit('detab', array($code));
            $code->replace('/\A\n+/', '');
            $code->replace('/\s+\z/', '');

            return "\n\n" . $this->getRenderer()->renderCodeBlock($code, $options) . "\n\n";
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'fencedCodeBlock';
    }

}