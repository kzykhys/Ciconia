<?php


class TagTest extends \PHPUnit_Framework_TestCase
{

    public function testSetText()
    {
        $tag = new \Ciconia\Common\Tag('p');
        $tag->setText('content');
        $text = $tag->getText();

        $this->assertInstanceOf('\\Ciconia\\Common\\Text', $text);
    }

    public function testRenderTag()
    {
        $tag = new \Ciconia\Common\Tag('div');
        $tag->setType(\Ciconia\Common\Tag::TYPE_BLOCK);
        $tag->setText('foo');
        $tag->setName('p');

        $this->assertEquals('<p>foo</p>', $tag);

        $tag->setEmptyTagSuffix('/>');
        $this->assertEquals('/>', $tag->getEmptyTagSuffix());
        $this->assertEquals(\Ciconia\Common\Tag::TYPE_BLOCK, $tag->getType());

        $tag->setText('');
        $this->assertEquals('<p></p>', $tag);
    }

    public function testAttributes()
    {
        $tag = new \Ciconia\Common\Tag('p');
        $tag->setAttribute('class', 'attr-class');
        $tag->setAttribute('id', 'attr-id');

        $this->assertEquals(array('class' => 'attr-class', 'id' => 'attr-id'), iterator_to_array($tag->getAttributes()));
    }

}