<?php

namespace Ciconia\Renderer;

use Ciconia\Common\Tag;
use Ciconia\Common\Text;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Renders markdown result to HTML format
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class HtmlRenderer implements RendererInterface
{

    /**
     * {@inheritdoc}
     */
    public function renderParagraph($content, array $options = array())
    {
        return '<p>' . $content . "</p>";
    }

    /**
     * {@inheritdoc}
     */
    public function renderHeader($content, array $options = array())
    {
        $options = $this->createResolver()
            ->setRequired(array('level'))
            ->setAllowedValues(array('level' => array(1, 2, 3, 4, 5, 6)))
            ->resolve($options);

        return sprintf('<h%2$s>%1$s</h%2$s>', $content, $options['level']);
    }

    /**
     * {@inheritdoc}
     */
    public function renderCodeBlock($content, array $options = array())
    {
        if (!$content instanceof Text) {
            $content = new Text($content);
        }

        $options = $this->createResolver()->resolve($options);

        $pre = new Tag('pre');
        $pre->setAttributes($options['attr']);

        $code = new Tag('code');
        $code->setText($content->append("\n"));

        $pre->setText($code->render());

        return $pre->render();
    }

    /**
     * {@inheritdoc}
     */
    public function renderCodeSpan($content, array $options = array())
    {
        return "<code>$content</code>";
    }

    /**
     * @param string|Text $content
     * @param array       $options
     *
     * @return string
     */
    public function renderLink($content, array $options = array())
    {
        $options = $this->createResolver()
            ->setRequired(array('href'))
            ->setDefaults(array('href' => '#', 'title' => ''))
            ->setAllowedTypes(array('href' => 'string', 'title' => 'string'))
            ->resolve($options);

        $tag = new Tag('a');
        $tag->setText($content);
        $tag->setAttribute('href', $options['href']);

        if ($options['title']) {
            $tag->setAttribute('title', $options['title']);
        }

        return $tag->render();
    }

    /**
     * {@inheritdoc}
     */
    public function renderBlockQuote($content, array $options = array())
    {
        return "<blockquote>\n$content\n</blockquote>";
    }

    /**
     * {@inheritdoc}
     */
    public function renderList($content, array $options = array())
    {
        if (!$content instanceof Text) {
            $content = new Text($content);
        }

        $options = $this->createResolver()
            ->setRequired(array('type'))
            ->setAllowedValues(array('type' => array('ul', 'ol')))
            ->setDefaults(array('type' => 'ul'))
            ->resolve($options);

        $tag = new Tag($options['type']);
        $tag->setText($content->prepend("\n"));
        $tag->setAttributes($options['attr']);

        return $tag->render();
    }

    /**
     * {@inheritdoc}
     */
    public function renderListItem($content, array $options = array())
    {
        return "<li>" . $content . "</li>";
    }

    /**
     * {@inheritdoc}
     */
    public function renderHorizontalRule(array $options = array())
    {
        return '<hr' . $this->getEmptyTagSuffix();
    }

    /**
     * {@inheritdoc}
     */
    public function renderImage($src, array $options = array())
    {
        $options = $this->createResolver()->resolve($options);

        $tag = new Tag('img');
        $tag
            ->setEmptyTagSuffix($this->getEmptyTagSuffix())
            ->setType(Tag::TYPE_INLINE)
            ->setAttribute('src', $src)
            ->setAttributes($options['attr']);

        return $tag->render();
    }

    /**
     * {@inheritdoc}
     */
    public function renderBoldText($text, array $options = array())
    {
        return '<strong>' . $text . '</strong>';
    }

    /**
     * {@inheritdoc}
     */
    public function renderItalicText($text, array $options = array())
    {
        return '<em>' . $text . '</em>';
    }

    /**
     * {@inheritdoc}
     */
    public function renderLineBreak(array $options = array())
    {
        return '<br' . $this->getEmptyTagSuffix();
    }

    /**
     * {@inheritdoc}
     */
    public function renderTag($tagName, $content, $tagType = Tag::TYPE_BLOCK, array $options = array())
    {
        $options = $this->createResolver()->resolve($options);

        $tag = new Tag($tagName);
        $tag->setType($tagType);
        $tag->setText($content);
        $tag->setAttributes($options['attr']);

        return $tag->render();
    }

    /**
     * @return OptionsResolver
     */
    protected function createResolver()
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(array('attr' => array()));
        $resolver->setAllowedTypes(array('attr' => 'array'));

        return $resolver;
    }

    /**
     * @return string
     */
    protected function getEmptyTagSuffix()
    {
        return '>';
    }

}