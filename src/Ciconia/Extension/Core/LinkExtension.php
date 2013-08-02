<?php

namespace Ciconia\Extension\Core;

use Ciconia\Common\Collection;
use Ciconia\Common\Text;
use Ciconia\Extension\ExtensionInterface;
use Ciconia\Renderer\RendererAwareInterface;
use Ciconia\Renderer\RendererAwareTrait;
use Ciconia\Markdown;

/**
 * Original source code from Markdown.pl
 *
 * > Copyright (c) 2004 John Gruber
 * > <http://daringfireball.net/projects/markdown/>
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class LinkExtension implements ExtensionInterface, RendererAwareInterface
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

        $markdown->on('initialize', array($this, 'initialize'), 30);
        $markdown->on('inline', array($this, 'processReferencedLink'), 40);
        $markdown->on('inline', array($this, 'processInlineLink'), 40);
        $markdown->on('inline', array($this, 'processAutoLink'), 50);
    }

    /**
     * Strips link definitions from text, stores the URLs and titles in hash references.
     *
     * @param Text  $text
     * @param array $options
     */
    public function initialize(Text $text, array $options = array())
    {
        /** @noinspection PhpUnusedParameterInspection */
        $text->replace('{
            ^[ ]{0,' . $options['tabWidth'] . '}\[(.+)\]:   # id = $1
              [ \t]*
              \n?               # maybe *one* newline
              [ \t]*
            <?(\S+?)>?          # url = $2
              [ \t]*
              \n?               # maybe one newline
              [ \t]*
            (?:
                (?<=\s)         # lookbehind for whitespace
                ["\'(]
                (.+?)           # title = $3
                ["\')]
                [ \t]*
            )?  # title is optional
            (?:\n+|\Z)
        }xm', function (Text $whole, Text $id, $url, $title = null) {
            $id->lower();
            $this->markdown->getUrlRegistry()->set($id, htmlspecialchars($url, ENT_QUOTES, 'UTF-8', false));

            if ($title) {
                $this->markdown->getTitleRegistry()->set($id, preg_replace('/"/', '&quot;', $title));
            }

            return '';
        });
    }

    /**
     * Handle reference-style links: [link text] [id]
     *
     * @param Text $text
     */
    public function processReferencedLink(Text $text)
    {
        /** @noinspection PhpUnusedParameterInspection */
        $text->replace('{
            (                   # wrap whole match in $1
              \[
                (' . $this->getNestedBrackets() . ')    # link text = $2
              \]

              [ ]?              # one optional space
              (?:\n[ ]*)?       # one optional newline followed by spaces

              \[
                (.*?)       # id = $3
              \]
            )
        }xs', function (Text $w, Text $whole, Text $linkText, Text $id = null) {
            if (is_null($id) || (string) $id == '') {
                $id = new Text($linkText);
                $id->lower();
            }

            if ($this->markdown->getUrlRegistry()->exists($id)) {
                $url = new Text($this->markdown->getUrlRegistry()->get($id));
                $url->escapeHtml();
                $result = new Text("<a href=\"$url\"");
                if ($this->markdown->getTitleRegistry()->exists($id)) {
                    $title = new Text($this->markdown->getTitleRegistry()->get($id));
                    $title->escapeHtml();
                    $result->append(" title=\"$title\"");
                }
                return $result->append(">$linkText</a>");
            } else {
                return $whole;
            }
        });
    }

    /**
     * Inline-style links: [link text](url "optional title")
     *
     * @param Text $text
     */
    public function processInlineLink(Text $text)
    {
        /** @noinspection PhpUnusedParameterInspection */
        $text->replace('{
            (               # wrap whole match in $1
              \[
                (' . $this->getNestedBrackets() . ')    # link text = $2
              \]
              \(            # literal paren
                [ \t]*
                <?(.*?)>?   # href = $3
                [ \t]*
                (           # $4
                  ([\'"])   # quote char = $5
                  (.*?)     # Title = $6
                  \5        # matching quote
                )?          # title is optional
              \)
            )
        }xs', function (Text $w, Text $whole, Text $linkText, Text $url, Text $a = null, Text $q = null, Text $title = null) {
            $url->escapeHtml();
            $result = new Text("<a href=\"$url\"");
            if ($title) {
                $title->replace('/"/', '&quot;')->escapeHtml();
                $result->append(" title=\"$title\"");
            }

            return $result->append(">$linkText</a>");
        });
    }

    /**
     * Make links out of things like `<http://example.com/>`
     *
     * @param Text $text
     */
    public function processAutoLink(Text $text)
    {
        $text->replace('{<((https?|ftp):[^\'">\s]+)>}', '<a '.'href="$1">$1</a>');

        /** @noinspection PhpUnusedParameterInspection */
        $text->replace('{
            <
            (?:mailto:)?
            (
                [-.\w]+
                \@
                [-a-z0-9]+(\.[-a-z0-9]+)*\.[a-z]+
            )
            >
        }ix', function (Text $w, Text $address) {
            $address  = "mailto:" . $address;

            $encode = array(
                function ($char) { return '&#' . ord($char) . ';'; },
                function ($char) { return '&#x' . dechex(ord($char)) . ';'; },
                function ($char) { return $char; }
            );

            $chars = new Collection(str_split($address));

            $chars->apply(function ($char) use ($encode) {
                if ($char == '@') {
                    return $encode[rand(0, 1)]($char);
                } elseif ($char != ':') {
                    $rand = rand(0, 100);
                    $key = ($rand > 90) ? 2 : ($rand < 45 ? 0 : 1);

                    return $encode[$key]($char);
                }

                return $char;
            });

            $address = $chars->join();
            $text = $chars->slice(7)->join();

            return "<a"." href=\"$address\">$text</a>";
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'link';
    }

    /**
     * @return string
     */
    protected function getNestedBrackets()
    {
        return str_repeat('(?>[^\[\]]+|\[', 7) . str_repeat('\])*', 7);
    }

}
