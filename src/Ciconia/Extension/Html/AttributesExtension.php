<?php

namespace Ciconia\Extension\Html;

use Ciconia\Common\Tag;
use Ciconia\Common\Text;
use Ciconia\Extension\ExtensionInterface;
use Ciconia\Markdown;

/**
 * [Experimental] Emmet-style HTML attributes
 *
 * @since 1.1
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class AttributesExtension implements ExtensionInterface
{

    /**
     * {@inheritdoc}
     */
    public function register(Markdown $markdown)
    {
        $markdown->on('tag', array($this, 'processBlockTags'));
    }

    /**
     * Parse emmet style attributes
     *
     * @param Tag $tag
     */
    public function processBlockTags(Tag $tag)
    {
        if ($tag->isInline()) {
            return;
        }

        $text = null;
        $tag->getText()->replace('/(^{([^:\(\)]+)}[ \t]*\n?|(?:[ \t]*|\n?){([^:\(\)]+)}\n*$)/', function (Text $w) use (&$text) {
            $text = $w->trim()->trim('{}');

            return '';
        });

        if ($text) {
            $tag->setAttributes($this->parseAttributes($text));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'htmlAttributes';
    }

    /**
     * @param Text $text
     *
     * @return array
     */
    protected function parseAttributes(Text $text)
    {
        $patterns = [
            'id'    => '/^#([a-zA-Z0-9_-]+)/',
            'class' => '/^\.([a-zA-Z0-9_-]+)/',
            'attr'  => '/^\[([^\]]+)\]/',
            'ident' => '/^(.)/'
        ];

        $tokens = [
            'id' => [], 'class' => [], 'attr' => [], 'ident' => []
        ];

        while (!$text->isEmpty()) {
            foreach ($patterns as $name => $pattern) {
                if ($text->match($pattern, $matches)) {
                    $tokens[$name][] = $matches[1];
                    $text->setString(
                        substr($text->getString(), strlen($matches[0]))
                    );

                    break;
                }
            }
        }

        $attributes = array();

        if (count($tokens['id'])) {
            $attributes['id'] = array_pop($tokens['id']);
        }

        if (count($tokens['class'])) {
            $attributes['class'] = implode(' ', $tokens['class']);
        }

        if (count($tokens['attr'])) {
            foreach ($tokens['attr'] as $raw) {
                $items = explode(' ', $raw);
                foreach ($items as $item) {
                    if (strpos($item, '=') !== false) {
                        list ($key, $value) = explode('=', $item);
                        $attributes[$key] = trim($value, '"');
                    } else {
                        $attributes[$item] = $item;
                    }
                }
            }
        }

        return $attributes;
    }

}