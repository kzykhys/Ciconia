<?php

use Ciconia\Common\Text;

class TextTest extends \PHPUnit_Framework_TestCase
{

    public $class = '\Ciconia\\Common\\Text';

    public function testStringExpression()
    {
        $text = new Text('test');
        $this->assertInternalType('string', (string) $text);
        $this->assertEquals('test', (string) $text);
    }

    public function testMatch()
    {
        $text = new Text('abcd1234efgh');
        $this->assertTrue($text->match('/\d{4}/'));
        $this->assertFalse($text->match('/^\d+/'));
    }

    public function testReplaceString()
    {
        $text = new Text('<tag></tag>');
        $this->assertEquals('<div></div>', $text->replace('/[a-z]+/', 'div'));
    }

    public function testReplaceCallback()
    {
        $text = new Text('<tag></tag>');
        $this->assertEquals('<div></div>', $text->replace('/[a-z]+/', function () {
            return 'div';
        }));
    }

    public function testSplit()
    {
        $text = new Text("line1\r\nline2\nline3");
        $items = $text->split('/\r?\n/');

        $this->assertCount(3, $items);
        $this->assertContainsOnlyInstancesOf($this->class, $items);
    }

    public function testLength()
    {
        $text = new Text('abcd---');
        $this->assertEquals(7, $text->getLength());

        $text = new Text('日本語');
        $this->assertEquals(3, $text->getLength());
    }

    public function testSerializable()
    {
        $text = new Text('Lorem Ipsum');

        $serialized = serialize($text);
        $text = unserialize($serialized);

        $this->assertInstanceOf($this->class, $text);
        $this->assertEquals('Lorem Ipsum', (string) $text);
    }

    public function testAppendPrependWrap()
    {
        $text = new Text('content');

        $expected = '<p>content</p>';
        $p = $text->append('</p>')->prepend('<p>');
        $this->assertEquals($expected, (string) $p);

        $text = new Text('content');
        $this->assertEquals($expected, (string) $text->wrap('<p>', '</p>'));
    }

    public function testCase()
    {
        $text = new Text('AbCd');

        $this->assertEquals('abcd', (string) $text->lower());
        $this->assertEquals('ABCD', (string) $text->upper());
    }

    public function testTrim()
    {
        $text = new Text('  #Test##    ');
        $this->assertEquals('#Test##', $text->trim());
        $this->assertEquals('Test', $text->trim('#'));

        $text = new Text('Test##    ');
        $this->assertEquals('Test##', $text->rtrim());
        $this->assertEquals('Test', $text->rtrim('#'));
    }

}