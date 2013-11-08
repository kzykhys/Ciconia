<?php

use Ciconia\Common\Collection;

/**
 * Tests Ciconia\Common\Collection
 *
 * @author Kazuyuki Hayashi <hayashi@siance.co.jp>
 */
class CollectionTest extends \PHPUnit_Framework_TestCase
{

    public function testAdd()
    {
        $collection = new Collection();
        $collection->add(1)->add(2);

        $array = iterator_to_array($collection);

        $this->assertEquals(1, $array[0]);
        $this->assertEquals(2, $array[1]);
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testGet()
    {
        $collection = new Collection();
        $collection->set('foo', 'bar');

        $this->assertEquals('bar', $collection->get('foo'));

        $collection->get('baz');
    }

    public function testContains()
    {
        $text = new \Ciconia\Common\Text('item1');

        $collection = new Collection();
        $collection->add($text);

        $this->assertTrue($collection->contains($text));
        $this->assertFalse($collection->contains(new \Ciconia\Common\Text('item1')));
    }

    public function testEach()
    {
        $collection = new Collection(array(1, 2, 3, 4));

        $test = array();

        $collection->each(function ($value) use (&$test) {
            $test[] = $value * 2;
        });

        $this->assertEquals(array(2, 4, 6, 8), $test);
    }

}