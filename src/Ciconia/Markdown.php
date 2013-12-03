<?php

namespace Ciconia;

use Ciconia\Common\Collection;
use Ciconia\Common\Text;
use Ciconia\Renderer\RendererInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sabre\Event\EventEmitter;

/**
 * Manages parser options and events
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class Markdown extends EventEmitter
{
    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var array
     */
    private $options;

    /**
     * @var HashRegistry
     */
    private $hashRegistry;

    /**
     * @var Collection
     */
    private $urlRegistry;

    /**
     * @var Collection
     */
    private $titleRegistry;

    /**
     * @var Text
     */
    private $rawContent;

    /**
     * Constructor
     *
     * @param Renderer\RendererInterface $renderer   A RendererInterface instance
     * @param Common\Text                $rawContent [optional] The whole content
     * @param array                      $options    [optional] An array of options
     */
    public function __construct(RendererInterface $renderer, Text $rawContent = null, array $options = array())
    {
        $this->renderer      = $renderer;
        $this->options       = $this->parseOptions($options);
        $this->hashRegistry  = new HashRegistry();
        $this->urlRegistry   = new Collection();
        $this->titleRegistry = new Collection();
        $this->rawContent    = $rawContent;
    }

    /**
     * @return HashRegistry
     */
    public function getHashRegistry()
    {
        return $this->hashRegistry;
    }

    /**
     * @return Collection
     */
    public function getTitleRegistry()
    {
        return $this->titleRegistry;
    }

    /**
     * @return Collection
     */
    public function getUrlRegistry()
    {
        return $this->urlRegistry;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return Text
     */
    public function getRawContent()
    {
        return $this->rawContent;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'tabWidth'       => 4,
            'nestedTagLevel' => 3,
            'strict'         => false
        ));

        $resolver->setAllowedTypes(array(
            'tabWidth'       => 'integer',
            'nestedTagLevel' => 'integer',
            'strict'         => 'bool'
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function buildParameters(array $parameters = array())
    {
        $parameters[] = $this->options;

        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function emit($event, array $parameters = array()) {

        $parameters = $this->buildParameters($parameters);
        return parent::emit($event, $parameters);

    }

    /**
     * @param array $options
     *
     * @return array
     */
    private function parseOptions(array $options = array())
    {
        $this->setDefaultOptions($resolver = new OptionsResolver());

        return $resolver->resolve($options);
    }

}
