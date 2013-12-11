<?php

namespace Ciconia\Common;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class Collection implements \IteratorAggregate, \Countable
{

    /**
     * @var array
     */
    private $objects;

    /**
     * Constructor
     *
     * @param array $objects [optional]
     */
    public function __construct(array $objects = array())
    {
        $this->objects = $objects;
    }

    /**
     * Appends a new value as the last element.
     *
     * @param mixed $object The value to append
     *
     * @return Collection
     */
    public function add($object)
    {
        $this->objects[] = $object;

        return $this;
    }

    /**
     * Sets the value at the specified index
     *
     * @param string $name   The index
     * @param mixed  $object The value
     *
     * @return Collection
     */
    public function set($name, $object)
    {
        $this->objects[(string)$name] = $object;

        return $this;
    }

    /**
     * Returns the value at the specified index
     *
     * @param string $name The index
     *
     * @throws \OutOfBoundsException When the index is invalid
     *
     * @return mixed
     */
    public function get($name)
    {
        if (!$this->exists($name)) {
            throw new \OutOfBoundsException(sprintf('Undefined offset "%s"', $name));
        }

        return $this->objects[(string)$name];
    }

    /**
     * Returns whether the requested index exists
     *
     * @param string $name The index
     *
     * @return bool True if the index is valid
     */
    public function exists($name)
    {
        return isset($this->objects[(string)$name]);
    }

    /**
     * Returns whether the requested value exists
     *
     * @param mixed $object The value
     *
     * @return bool True if the value exists
     */
    public function contains($object)
    {
        return array_search($object, $this->objects, true) !== false;
    }

    /**
     * Remove
     *
     * @param string $name The index
     *
     * @return Collection
     */
    public function remove($name)
    {
        if ($this->exists($name)) {
            unset($this->objects[$name]);
        }

        return $this;
    }

    /**
     * Join array elements with a string
     *
     * @param string $glue [optional] Defaults to an empty string.
     *
     * @return string
     */
    public function join($glue = '')
    {
        return implode($glue, $this->objects);
    }

    /**
     * Extract a slice of the array
     *
     * @param integer $offset If offset is non-negative, the sequence will start at that offset in the array.
     *                        If offset is negative, the sequence will start that far from the end of the array.
     * @param integer $length If length is given and is positive, then the sequence will have up to that many elements in it.
     *                        If the array is shorter than the length, then only the available array elements will be present.
     *                        If length is given and is negative then the sequence will stop that many elements from the end of the array.
     *                        If it is omitted, then the sequence will have everything from offset up until the end of the array.
     *
     * @return Collection
     */
    public function slice($offset, $length = null)
    {
        return new Collection(array_slice($this->objects, $offset, $length));
    }

    /**
     * Execute the callback for each element
     *
     * @param callable $callable function ($value, $key) {}
     *
     * @return Collection
     */
    public function each(callable $callable)
    {
        foreach ($this->objects as $key => $value) {
            if (false === call_user_func_array($callable, array($value, $key))) {
                break;
            }
        }

        return $this;
    }

    /**
     * Applies the callback to the elements
     *
     * @param callable $callable
     *
     * @return Collection
     */
    public function apply(callable $callable)
    {
        $this->objects = array_map($callable, $this->objects);

        return $this;
    }

    /**
     * Filters elements using a callback function
     *
     * @param callable $callable
     *
     * @return Collection
     */
    public function filter(callable $callable = null)
    {
        return new static(array_filter($this->objects, $callable));
    }

    /**
     * Retrieve an external iterator
     *
     * @return \Traversable An instance of an object implementing <b>Iterator</b> or <b>Traversable</b>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->objects);
    }

    /**
     * Count elements of an object
     *
     * @return int The custom count as an integer. The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->objects);
    }

}