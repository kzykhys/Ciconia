<?php

namespace Ciconia\Common;

/**
 * HTML/XHTML/XML tag definition
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class Tag
{

    /**
     * Block level tag
     */
    const TYPE_BLOCK = 'block';

    /**
     * Inline level tag
     */
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
     * Creates a new Tag object
     *
     * @param string $name The name of the tag
     *
     * @return Tag
     */
    public static function create($name)
    {
        return new static($name);
    }

    /**
     * Constructor
     *
     * @param string $name The name of the tag
     */
    public function __construct($name)
    {
        $this->name       = $name;
        $this->attributes = new Collection();
        $this->text       = new Text();
    }

    /**
     * Sets the inner text
     *
     * @param Text|string $text A string to set
     *
     * @return Tag
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
     * Gets the inner text
     *
     * @return Text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets the name of the tag
     *
     * @param string $name The name of the tag
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Returns the name of the tag
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the empty tag suffix
     *
     * @param string $emptyTagSuffix The suffix
     *
     * @return Tag
     */
    public function setEmptyTagSuffix($emptyTagSuffix)
    {
        $this->emptyTagSuffix = $emptyTagSuffix;

        return $this;
    }

    /**
     * Returns the empty tag suffix
     *
     * @return string The suffix
     */
    public function getEmptyTagSuffix()
    {
        return $this->emptyTagSuffix;
    }

    /**
     * Sets the type of the tag (Tag::TYPE_BLOCK or Tag::TYPE_INLINE)
     *
     * @param string $type The type of the tag
     *
     * @return Tag
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Returns the type of the tag
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isBlock()
    {
        return $this->type === self::TYPE_BLOCK;
    }

    /**
     * @return bool
     */
    public function isInline()
    {
        return $this->type === self::TYPE_INLINE;
    }

    /**
     * Sets an attribute
     *
     * @param string $attribute The name of an attribute
     * @param string $value     [optional] The value of an attribute
     *
     * @return Tag
     */
    public function setAttribute($attribute, $value = null)
    {
        $this->attributes->set($attribute, $value);

        return $this;
    }

    /**
     * Sets attributes
     *
     * @param array $attributes An array of attributes
     *
     * @return Tag
     */
    public function setAttributes(array $attributes)
    {
        foreach ($attributes as $attr => $value) {
            $this->setAttribute($attr, $value);
        }

        return $this;
    }

    /**
     * Returns a collection of attributes
     *
     * @return Collection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Renders the tag
     *
     * @return string The HTML string
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
                return (string)$html->append('>')->append('</')->append($this->getName())->append('>');
            } else {
                return (string)$html->append($this->emptyTagSuffix);
            }
        }

        return (string)$html
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