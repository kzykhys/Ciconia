<?php

namespace Ciconia;

use Ciconia\Common\Collection;
use Ciconia\Common\Text;
use Ciconia\Event\EmitterAwareInterface;
use Ciconia\Extension\Core;
use Ciconia\Extension\ExtensionInterface;
use Ciconia\Renderer\RendererAwareInterface;
use Ciconia\Renderer\HtmlRenderer;
use Ciconia\Renderer\RendererInterface;

/**
 * Ciconia - The New Markdown Parser
 *
 * This is just the central point to manage `renderer` and `extensions`.
 *
 * The `Core` extensions are based on Markdown.pl
 * The `Gfm` extensions are based on Github Flavored Markdown
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class Ciconia
{

    const VERSION = '1.0.1';

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var Collection|ExtensionInterface[]
     */
    private $extensions;

    /**
     * @param RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer = null)
    {
        $this->extensions = new Collection();
        $this->renderer = $renderer;

        if (is_null($this->renderer)) {
            $this->setRenderer($this->getDefaultRenderer());
        }

        $this->addExtensions($this->getDefaultExtensions());
    }

    /**
     * @param string $text
     * @param array  $options
     *
     * @return string
     */
    public function render($text, array $options = array())
    {
        $text = new Text($text);
        $markdown = new Markdown($this->renderer, $text, $options);

        $this->registerExtensions($markdown);

        $markdown->emit('initialize', array($text));
        $markdown->emit('block', array($text));
        $markdown->emit('finalize', array($text));

        return (string) $text;
    }

    /**
     * @param \Ciconia\Renderer\RendererInterface $renderer
     *
     * @return Ciconia
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * @return \Ciconia\Renderer\RendererInterface
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param ExtensionInterface $extension
     *
     * @return Ciconia
     */
    public function addExtension(ExtensionInterface $extension)
    {
        $this->extensions->set($extension->getName(), $extension);

        return $this;
    }

    /**
     * @param ExtensionInterface[] $extensions
     *
     * @return Ciconia
     */
    public function addExtensions(array $extensions)
    {
        foreach ($extensions as $extension) {
            $this->addExtension($extension);
        }

        return $this;
    }

    /**
     * @param string|object $extension
     *
     * @return Ciconia
     */
    public function removeExtension($extension)
    {
        if ($extension instanceof ExtensionInterface) {
           $extension = $extension->getName();
        }

        $this->extensions->remove($extension);

        return $this;
    }

    /**
     * @param string|object $extension
     *
     * @return boolean
     */
    public function hasExtension($extension)
    {
        if ($extension instanceof ExtensionInterface) {
            $extension = $extension->getName();
        }

        return $this->extensions->exists($extension);
    }

    /**
     * @return RendererInterface
     */
    protected function getDefaultRenderer()
    {
        return new HtmlRenderer();
    }

    /**
     * @return ExtensionInterface[]
     */
    protected function getDefaultExtensions()
    {
        return array(
            new Core\WhitespaceExtension(),
            new Core\HeaderExtension(),
            new Core\ParagraphExtension(),
            new Core\HtmlBlockExtension(),
            new Core\LinkExtension(),
            new Core\HorizontalRuleExtension(),
            new Core\ListExtension(),
            new Core\CodeExtension(),
            new Core\BlockQuoteExtension(),
            new Core\ImageExtension(),
            new Core\InlineStyleExtension(),
            new Core\EscaperExtension()
        );
    }

    /**
     * @param Markdown $markdown
     */
    protected function registerExtensions(Markdown $markdown)
    {
        foreach ($this->extensions as $extension) {
            if ($extension instanceof RendererAwareInterface) {
                $extension->setRenderer($this->renderer);
            }

            if ($extension instanceof EmitterAwareInterface) {
                $extension->setEmitter($markdown);
            }

            $extension->register($markdown);
        }
    }

}