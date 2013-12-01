<?php

namespace Ciconia\Renderer;

use Ciconia\Common\Tag;
use Ciconia\Common\Text;

/**
 * RendererInterface must be implemented by classes that renders result of Markdown
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
interface RendererInterface
{

    /**
     * @api
     *
     * @param string|Text $content
     * @param array       $options
     *
     * @return string
     */
    public function renderParagraph($content, array $options = array());

    /**
     * @api
     *
     * @param string|Text $content
     * @param array       $options
     *
     * @return string
     */
    public function renderHeader($content, array $options = array());

    /**
     * @api
     *
     * @param string|Text $content
     * @param array       $options
     *
     * @return string
     */
    public function renderCodeBlock($content, array $options = array());

    /**
     * @api
     *
     * @param string|Text $content
     * @param array       $options
     *
     * @return string
     */
    public function renderCodeSpan($content, array $options = array());

    /**
     * @api
     *
     * @param string|Text $content
     * @param array       $options
     *
     * @return string
     */
    public function renderLink($content, array $options = array());

    /**
     * @api
     *
     * @param string|Text $content
     * @param array       $options
     *
     * @return string
     */
    public function renderBlockQuote($content, array $options = array());

    /**
     * @api
     *
     * @param string|Text $content
     * @param array       $options
     *
     * @return string
     */
    public function renderList($content, array $options = array());

    /**
     * @api
     *
     * @param string|Text $content
     * @param array       $options
     *
     * @return string
     */
    public function renderListItem($content, array $options = array());

    /**
     * @api
     *
     * @param array $options
     *
     * @return string
     */
    public function renderHorizontalRule(array $options = array());

    /**
     * @api
     *
     * @param string $src
     * @param array  $options
     *
     * @return string
     */
    public function renderImage($src, array $options = array());

    /**
     * @api
     *
     * @param string|Text $text
     * @param array       $options
     *
     * @return string
     */
    public function renderBoldText($text, array $options = array());

    /**
     * @api
     *
     * @param string|Text $text
     * @param array       $options
     *
     * @return string
     */
    public function renderItalicText($text, array $options = array());

    /**
     * @api
     *
     * @param array $options
     *
     * @return string
     */
    public function renderLineBreak(array $options = array());

    /**
     * @param string $tagName
     * @param string $content
     * @param string $tagType
     * @param array  $options
     *
     * @return mixed
     */
    public function renderTag($tagName, $content, $tagType = Tag::TYPE_BLOCK, array $options = array());

}