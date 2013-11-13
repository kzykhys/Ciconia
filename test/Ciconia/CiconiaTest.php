<?php

/**
 * @author Kazuyuki Hayashi <hayashi@siance.co.jp>
 */
class CiconiaTest extends \PHPUnit_Framework_TestCase
{

    public function testManipulateExtensions()
    {
        $md = new \Ciconia\Ciconia();
        $this->assertTrue($md->hasExtension(new \Ciconia\Extension\Core\EscaperExtension()));
        $this->assertFalse($md->removeExtension(new \Ciconia\Extension\Core\EscaperExtension())->hasExtension('escaper'));
        $this->assertTrue($md->addExtension(new \Ciconia\Extension\Gfm\InlineStyleExtension())->hasExtension('gfmInlineStyle'));
    }

    public function testRenderer()
    {
        $md = new \Ciconia\Ciconia(new \Ciconia\Renderer\XhtmlRenderer());
        $this->assertInstanceOf('Ciconia\\Renderer\\XhtmlRenderer', $md->getRenderer());
    }

    public function testRunTwice()
    {
        $ciconia = new \Ciconia\Ciconia();
        $markdown = file_get_contents(__DIR__.'/Resources/core/markdown-testsuite/link-idref.md');

        $this->assertEquals($ciconia->render($markdown), $ciconia->render($markdown));
    }

}