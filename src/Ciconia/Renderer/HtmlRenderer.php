<?php

namespace Ciconia\Renderer;

use Ciconia\Common\Tag;
use Ciconia\Common\Text;
use Ciconia\Event\EmitterAwareInterface;
use Ciconia\Event\EmitterAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Renders markdown result to HTML format
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class HtmlRenderer implements RendererInterface, EmitterAwareInterface
{

    use EmitterAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function renderParagraph($content, array $options = array())
    {
        $options = $this->createResolver()->resolve($options);

        $tag = new Tag('p');
        $tag->setText($content);
        $tag->setAttributes($options['attr']);

        $this->getEmitter()->emit('tag', [$tag]);

        return $tag->render();
    }

    /**
     * {@inheritdoc}
     */
    public function renderHeader($content, array $options = array())
    {
        $options = $this->createResolver()
            ->setRequired(['level'])
            ->setAllowedValues(['level' => [1, 2, 3, 4, 5, 6]])
            ->resolve($options);

        $tag = new Tag('h' . $options['level']);
        $tag->setAttributes($options['attr']);
        $tag->setText($content);

        $this->getEmitter()->emit('tag', [$tag]);

        return $tag->render();
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

        $tag = Tag::create('pre')
            ->setAttributes($options['attr'])
            ->setText(
                Tag::create('code')
                    ->setText($content->append("\n"))
                    ->render()
            );

        $this->getEmitter()->emit('tag', [$tag]);

        return $tag->render();
    }

    /**
     * {@inheritdoc}
     */
    public function renderCodeSpan($content, array $options = array())
    {
        $tag = new Tag('code');
        $tag->setType(Tag::TYPE_INLINE);
        $tag->setText($content);

        $this->getEmitter()->emit('tag', [$tag]);

        return $tag->render();
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

        $this->getEmitter()->emit('tag', [$tag]);

        return $tag->render();
    }

    /**
     * {@inheritdoc}
     */
    public function renderBlockQuote($content, array $options = array())
    {
        $tag = Tag::create('blockquote')
            ->setText($content->wrap("\n", "\n"));

        $this->getEmitter()->emit('tag', [$tag]);

        return $tag->render();
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

        $this->getEmitter()->emit('tag', [$tag]);

        return $tag->render();
    }

    /**
     * {@inheritdoc}
     */
    public function renderListItem($content, array $options = array())
    {
        $tag = Tag::create('li')
            ->setText($content);

        $this->getEmitter()->emit('tag', [$tag]);

        return $tag->render();
    }

    /**
     * {@inheritdoc}
     */
    public function renderHorizontalRule(array $options = array())
    {
        $tag = Tag::create('hr')
            ->setType(Tag::TYPE_INLINE)
            ->setEmptyTagSuffix($this->getEmptyTagSuffix());

        $this->getEmitter()->emit('tag', [$tag]);

        return $tag->render();
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

        $this->getEmitter()->emit('tag', [$tag]);

        return $tag->render();
    }

    /**
     * {@inheritdoc}
     */
    public function renderBoldText($text, array $options = array())
    {
        $tag = Tag::create('strong')
            ->setText($text);

        $this->getEmitter()->emit('tag', [$tag]);

        return $tag->render();
    }

    /**
     * {@inheritdoc}
     */
    public function renderItalicText($text, array $options = array())
    {
        $tag = Tag::create('em')
            ->setText($text);

        $this->getEmitter()->emit('tag', [$tag]);

        return $tag->render();
    }

    /**
     * {@inheritdoc}
     */
    public function renderLineBreak(array $options = array())
    {
        $tag = Tag::create('br')
            ->setType(Tag::TYPE_INLINE)
            ->setEmptyTagSuffix($this->getEmptyTagSuffix());

        $this->getEmitter()->emit('tag', [$tag]);

        return $tag->render();
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

        $this->getEmitter()->emit('tag', [$tag]);

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