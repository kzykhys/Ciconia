<?php

namespace Ciconia\Extension\Textile;

use Ciconia\Common\Tag;
use Ciconia\Common\Text;
use Ciconia\Event\EmitterAwareInterface;
use Ciconia\Event\EmitterAwareTrait;
use Ciconia\Extension\ExtensionInterface;
use Ciconia\Markdown;
use Ciconia\Renderer\RendererAwareInterface;
use Ciconia\Renderer\RendererAwareTrait;

/**
 * Textile Definition Lists
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class DefinitionListExtension implements ExtensionInterface, RendererAwareInterface, EmitterAwareInterface
{

    use RendererAwareTrait;
    use EmitterAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function register(Markdown $markdown)
    {
        $markdown->on('block', array($this, 'processDefinitionList'), 30);
        //$markdown->on('block', array($this, 'processWikiDefinitionList'), 30);
    }

    /**
     * @param Text $text
     */
    public function processDefinitionList(Text $text)
    {
        $text->replace(
            '{
                (                       #1 whole match
                    - [ \t]*           # dL starts with a dash
                    (.+?) [ \t]* :=     #1 DT [dt and dd are splitted by :=]
                    (                   #2 DD
                        \n? .+? =:       # Multiline contents will ends with =:
                        |
                        [^\n]+?   # A line
                    )
                    \n
                ){1,}
                \n+
            }smx',
            function (Text $w) {
                $this->processListItems($w);
                $dl = Tag::create('dl')->setText($w->trim()->wrap("\n", "\n"));

                return $dl->render();
            }
        );
    }

    /**
     * @param Text $text
     */
    public function processListItems(Text $text)
    {
        /** @noinspection PhpUnusedParameterInspection */
        $text->replace(
            '{
                (                       #1 whole match
                    - [ \t]*           # dL starts with a dash
                    ([^\n]+) [ \t]* :=     #2 DT [dt and dd are splitted by :=]
                    (                   #3
                        \n (.+) (=:)   #4 DD>P
                        |
                        [ \t]* ([^\n]+)        #6 DD
                    )
                )
                \n
            }smx',
            function (Text $w, Text $item, Text $definition, Text $c, Text $dd1, Text $multiLine, Text $dd2 = null) {
                $dt = Tag::create('dt')->setText($definition->trim());
                $dd = Tag::create('dd');

                if (!$multiLine->isEmpty()) {
                    $dd1->trim();
                    $this->getEmitter()->emit('outdent', [$dd1]);
                    $dd->setText($this->getRenderer()->renderParagraph($dd1));
                } else {
                    $dd->setText($dd2->trim());
                }

                return $dt->render() . "\n" . $dd->render() . "\n";
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'definitionList';
    }

}