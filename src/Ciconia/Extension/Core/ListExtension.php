<?php

namespace Ciconia\Extension\Core;

use Ciconia\Common\Text;
use Ciconia\Event\EmitterAwareInterface;
use Ciconia\Event\EmitterAwareTrait;
use Ciconia\Extension\ExtensionInterface;
use Ciconia\Renderer\RendererAwareInterface;
use Ciconia\Renderer\RendererAwareTrait;
use Ciconia\Markdown;

/**
 * Form HTML ordered (numbered) and unordered (bulleted) lists.
 *
 * Original source code from Markdown.pl
 *
 * > Copyright (c) 2004 John Gruber
 * > <http://daringfireball.net/projects/markdown/>
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class ListExtension implements ExtensionInterface, RendererAwareInterface, EmitterAwareInterface
{

    use RendererAwareTrait;
    use EmitterAwareTrait;

    /**
     * @var Markdown
     */
    private $markdown;

    /**
     * @var string
     */
    private $ul = '[*+-]';

    /**
     * @var string
     */
    private $ol = '\d+[.]';

    /**
     * {@inheritdoc}
     */
    public function register(Markdown $markdown)
    {
        $this->markdown = $markdown;

        $markdown->on('block', array($this, 'processList'), 30);
    }

    /**
     * Form HTML ordered (numbered) and unordered (bulleted) lists.
     *
     * @param Text  $text
     * @param array $options
     * @param int   $level
     */
    public function processList(Text $text, array $options = array(), $level = 0)
    {
        $lessThanTab = $options['tabWidth'] - 1;

        $wholeList = '
            #(                                 # $1 = whole list
              (                               # $2
                [ ]{0,' . $lessThanTab . '}
                (' . $this->getPattern() . ') # $3 = first list item marker
                [ \t]+
              )
              (?s:.+?)
              (?:                             # $4
                  \z
                |
                  \n{2,}
                  (?=\S)
                  (?!                         # Negative lookahead for another list item marker
                    [ \t]*
                    ' . $this->getPattern() . '[ \t]+
                  )
              )
            #)
        ';

        /** @noinspection PhpUnusedParameterInspection */
        $callback = function (Text $list, Text $marker) use ($options, $level) {
            $type = preg_match('{' . $this->ul . '}', $marker) ? 'ul' : 'ol';
            $list->replace('/\n{2,}/', "\n\n\n");
            $this->processListItems($list, $options, $level);

            return $this->getRenderer()->renderList($list, array('type' => $type)) . "\n\n";
        };

        if ($level) {
            $text->replace("{^$wholeList}mx", $callback);
        } else {
            $text->replace('{(?:(?<=\n\n)|\A\n?)' . $wholeList . '}mx', $callback);
        }
    }

    /**
     * Process the contents of a single ordered or unordered list, splitting it into individual list items.
     *
     * @param Text  $list
     * @param array $options
     * @param int   $level
     */
    public function processListItems(Text $list, array $options = array(), $level = 0)
    {
        $list->replace('/\n{2,}\z/', "\n");

        /** @noinspection PhpUnusedParameterInspection */
        $list->replace('{
            (\n)?                                 # leading line = $1
            (^[ \t]*)                             # leading whitespace = $2
            (' . $this->getPattern() . ') [ \t]+  # list marker = $3
            ((?s:.+?)                             # list item text   = $4
            (\n{1,2}))
            (?= \n* (\z | \2 (' . $this->getPattern() . ') [ \t]+))
        }mx', function (Text $w, Text $leadingLine, Text $ls, Text $m, Text $item) use ($options, $level) {
            if ((string)$leadingLine or $item->match('/\n{2,}/')) {
                $this->markdown->emit('outdent', array($item));
                $this->markdown->emit('block', array($item));
            } else {
                $this->markdown->emit('outdent', array($item));
                $this->processList($item, $options, ++$level);
                $item->rtrim();
                $this->markdown->emit('inline', array($item));
            }

            return $this->getRenderer()->renderListItem($item) . "\n";
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'list';
    }

    /**
     * @return string
     */
    protected function getPattern()
    {
        return '(?:' . $this->ul . '|' . $this->ol . ')';
    }

}
