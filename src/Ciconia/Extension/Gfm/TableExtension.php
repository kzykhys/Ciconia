<?php

namespace Ciconia\Extension\Gfm;

use Ciconia\Common\Collection;
use Ciconia\Common\Tag;
use Ciconia\Common\Text;
use Ciconia\Extension\ExtensionInterface;
use Ciconia\Markdown;
use Ciconia\Renderer\HtmlRenderer;
use Ciconia\Renderer\RendererAwareInterface;
use Ciconia\Renderer\RendererAwareTrait;

/**
 * Gfm tables
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class TableExtension implements ExtensionInterface, RendererAwareInterface
{

    use RendererAwareTrait;

    /**
     * @var Markdown
     */
    private $markdown;

    /**
     * @var string
     */
    private $hash;

    /**
     * {@inheritdoc}
     */
    public function register(Markdown $markdown)
    {
        $this->markdown = $markdown;
        $this->hash = '{gfm:table:escape(' . md5('|') . ')}';

        if ($this->getRenderer() instanceof HtmlRenderer) {
            // Output format depends on HTML for now
            $markdown->on('block', array($this, 'processTable'));
        }
    }

    /**
     * Gfm tables
     *
     * @param Text  $text
     * @param array $options
     */
    public function processTable(Text $text, array $options = array())
    {
        $text->replace('/^
            (?:[ ]{0,3}      #  table header
                (?:\|?)      #  optional outer pipe
                (.*?)        #1 table header
                (?:\|?)      #  optional outer pipe
            )\n
            (?:[ ]{0,3}      #  second line
                (?:\|?)      #  optional outer pipe
                ([-:| ]+?)   #2 dashes and pipes
                (?:\|?)      #  optional outer pipe
            )\n
            [ ]{0,3}(        #3 table body
                (?:.*\n?)*
            )\n\n
        /mx', function (Text $w, Text $header, Text $rule, Text $body) use ($options) {
            // Escape pipe to hash, so you can include pipe in cells by escaping it like this: `\\|`
            $this->escapePipes($header);
            $this->escapePipes($rule);
            $this->escapePipes($body);

            $baseTags = $this->createBaseTags($rule->split('/\|/'));

            $headerCells = new Collection();
            $bodyRows = new Collection();

            $header->split('/\|/')->each(function (Text $cell, $index) use ($baseTags, &$headerCells) {
                /* @var Tag $tag */
                $tag = clone $baseTags->get($index);
                $tag->setName('th');
                $this->markdown->emit('inline', array($cell));
                $tag->setText($cell->trim());

                $headerCells->add($tag);
            });

            $body->split('/\n/')->each(function (Text $row) use ($baseTags, &$bodyRows) {
                $row->trim()->trim('|');
                $cells = new Collection();
                $row->split('/\|/')->each(function (Text $cell, $index) use (&$baseTags, &$cells) {
                    /* @var Tag $tag */
                    $tag = clone $baseTags->get($index);
                    $this->markdown->emit('inline', array($cell));
                    $tag->setText($cell->trim());

                    $cells->add($tag);
                });

                $bodyRows->add($cells);
            });

            $tHeadRow = new Tag('tr');
            $tHeadRow->setText("\n" . $headerCells->join("\n") . "\n");

            $tHead = new Tag('thead');
            $tHead->setText("\n" . $tHeadRow . "\n");

            $tBody = new Tag('tbody');

            $bodyRows->apply(function (Collection $row) use (&$options) {
                $tr = new Tag('tr');
                $tr->setText("\n" . $row->join("\n") . "\n");

                return $tr;
            });

            $tBody->setText("\n" .$bodyRows->join("\n") . "\n");

            $table = new Tag('table');
            $table->setText("\n" . $tHead . "\n" . $tBody . "\n");

            return $table->render() . "\n\n";
        });
    }

    /**
     * @param Collection $rules
     *
     * @return Collection|\Ciconia\Common\Tag[]
     */
    protected function createBaseTags(Collection $rules)
    {
        /* @var Collection|Tag[] $baseTags */
        $baseTags = new Collection();

        $rules->each(function (Text $cell) use (&$baseTags) {
            $cell->trim();
            $tag = new Tag('td');

            if ($cell->match('/^-.*:$/')) {
                $tag->setAttribute('align', 'right');
            } elseif ($cell->match('/^:.*:$/')) {
                $tag->setAttribute('align', 'center');
            }

            $baseTags->add($tag);
        });

        return $baseTags;
    }

    /**
     * @param Text $text
     */
    protected function escapePipes(Text $text)
    {
        $text->replaceString('\\|', $this->hash);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'table'; // Not gfmTable
    }

}