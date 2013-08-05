<?php

namespace Ciconia\Common;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class Tag
{

    const TYPE_BLOCK = 'block';
    const TYPE_INLINE = 'inline';

    /**
     * @var string
     */
    private $name;

    /**
     * @var Text
     */
    private $text;

    /**
     * @var Collection
     */
    private $attributes;

    /**
     * @var string
     */
    private $type = self::TYPE_BLOCK;

    /**
     * @var string
     */
    private $emptyTagSuffix = '>';

    /**
     * @param string $name The name of the tag
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->attributes = new Collection();
        $this->text = new Text();
    }

    /**
     * @param \Ciconia\Common\Text|string $text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        if (!$this->text instanceof Text) {
            $this->text = new Text($this->text);
        }

        return $this;
    }

    /**
     * @return \Ciconia\Common\Text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $emptyTagSuffix
     *
     * @return $this
     */
    public function setEmptyTagSuffix($emptyTagSuffix)
    {
        $this->emptyTagSuffix = $emptyTagSuffix;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmptyTagSuffix()
    {
        return $this->emptyTagSuffix;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $attribute
     * @param $value
     *
     * @return $this
     */
    public function setAttribute($attribute, $value = null)
    {
        $this->attributes->set($attribute, $value);

        return $this;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        foreach ($attributes as $attr => $value) {
            $this->setAttribute($attr, $value);
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return string
     */
    public function render()
    {
        $html = new Text();
        $html
            ->append('<')
            ->append($this->getName());

        foreach ($this->attributes as $name => $value) {
            $html->append(' ')
                ->append($name)
                ->append('=')
                ->append('"')
                ->append($value)
                ->append('"');
        }

        if ($this->text->isEmpty()) {
            if ($this->type == self::TYPE_BLOCK) {
                return (string) $html->append('>')->append('</')->append($this->getName())->append('>');
            } else {
                return (string) $html->append($this->emptyTagSuffix);
            }
        }

        return (string) $html
            ->append('>')
            ->append($this->text)
            ->append('</')
            ->append($this->getName())
            ->append('>');
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

}