<?php


class CiconiaTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return array
     */
    public function provider()
    {
        $patterns = array();

        $finder = new \Symfony\Component\Finder\Finder();
        $finder->in(__DIR__.'/Resources/markdown')->files()->name('*.md')->notName('link-automatic-email.md');

        /* @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $name = preg_replace('/\.(md|out)$/', '', $file->getFilename());
            $patterns[] = array(
                $name,
                $file->getContents(),
                trim(file_get_contents(preg_replace('/\.md$/', '.out', $file->getPathname())))
            );
        }

        return $patterns;
    }

    /**
     * @param $name
     * @param $markdown
     * @param $expected
     *
     * @dataProvider provider
     */
    public function testBasicMarkdown($name, $markdown, $expected)
    {
        $md = new \Ciconia\Ciconia(new \Ciconia\Renderer\XhtmlRenderer());

        $expected = str_replace("\r\n", "\n", $expected);
        $expected = str_replace("\r", "\n", $expected);

        $this->assertEquals($expected, $md->render($markdown), $name);
    }

    /**
     *
     */
    public function testAutolinkEmail()
    {
        $md = new \Ciconia\Ciconia();

        $raw = 'Email <'.'test@example.com>';
        $out = $md->render($raw);
        $expected = '<p>Email <a href="mailto:test@example.com">test@example.com</a></p>';

        $this->assertEquals($expected, html_entity_decode($out));
    }

    public function testGfmMultipleUnderscore()
    {
        $md = new \Ciconia\Ciconia();
        $md->addExtension(new \Ciconia\Extension\Gfm\InlineStyleExtension());
        $md->addExtension(new \Ciconia\Extension\Gfm\WhiteSpaceExtension());

        $raw = 'foo_bar_baz';
        $out = $md->render($raw);

        $this->assertEquals('<p>foo_bar_baz</p>', $out);
    }

    public function testGfmStrikeThrough()
    {
        $md = new \Ciconia\Ciconia();
        $md->addExtension(new \Ciconia\Extension\Gfm\InlineStyleExtension());

        $raw = '~~strike~~';
        $out = $md->render($raw);

        $this->assertEquals('<p><del>strike</del></p>', $out);
    }

    public function testGfmLineBreaks()
    {
        $md = new \Ciconia\Ciconia();
        $md->addExtension(new \Ciconia\Extension\Gfm\InlineStyleExtension());
        $md->addExtension(new \Ciconia\Extension\Gfm\WhiteSpaceExtension());

        $this->assertEquals("<p>foo<br>\nbar</p>", $md->render("foo\nbar"));
        $this->assertEquals("<p>apple<br>\npear<br>\norange</p>\n\n<p>ruby<br>\npython<br>\nerlang</p>", $md->render("apple\npear\norange\n\nruby\npython\nerlang"));
    }

    /**
     * Disable this test because Github's gfm sample page seems to be broken
     */
    public function XtestGfm()
    {
        $this->markTestSkipped();

        $md = new \Ciconia\Ciconia();
        $md->addExtension(new \Ciconia\Extension\Gfm\InlineStyleExtension());
        $md->addExtension(new \Ciconia\Extension\Gfm\WhiteSpaceExtension());

        $out = $md->render(file_get_contents(__DIR__.'/Resources/gfm/sample-content.md'));
        $expected = file_get_contents(__DIR__.'/Resources/gfm/sample-content.out');

        $this->assertEquals($expected, $out);
    }

    public function testGfmFencedCodeBlock()
    {
        $md = new \Ciconia\Ciconia();
        $md->addExtension(new \Ciconia\Extension\Gfm\InlineStyleExtension());
        $md->addExtension(new \Ciconia\Extension\Gfm\WhiteSpaceExtension());
        $md->addExtension(new \Ciconia\Extension\Gfm\FencedCodeBlockExtension());

        $out = $md->render("\n\n    code block\n    code block\n\n```\n<?php\n\nclass Test\n{\n\n}\n```\n\nheader\n------");
        $this->assertEquals("<pre><code>code block\ncode block\n</code></pre>\n\n<pre><code>&lt;?php\n\nclass Test\n{\n\n}\n</code></pre>\n\n<h2>header</h2>", $out);

        $out = $md->render("\n\n    code block\n    code block\n\n``` php\n<?php\n\nclass Test\n{\n\n}\n```\n\nheader\n------");
        $this->assertEquals("<pre><code>code block\ncode block\n</code></pre>\n\n<pre class=\"prettyprint lang-php\"><code>&lt;?php\n\nclass Test\n{\n\n}\n</code></pre>\n\n<h2>header</h2>", $out);
    }

    public function testGfmTaskList()
    {
        $md = new \Ciconia\Ciconia();
        $md->addExtension(new \Ciconia\Extension\Gfm\InlineStyleExtension());
        $md->addExtension(new \Ciconia\Extension\Gfm\WhiteSpaceExtension());
        $md->addExtension(new \Ciconia\Extension\Gfm\FencedCodeBlockExtension());
        $md->addExtension(new \Ciconia\Extension\Gfm\TaskListExtension());

        $out = $md->render("\n\n- [ ] Task 1\n- [x] Completed task\n- [x] Task 2\n\n");
        $this->assertEquals("<ul>\n<li><input type=\"checkbox\"> Task 1</li>\n<li><input type=\"checkbox\" checked=\"checked\"> Completed task</li>\n<li><input type=\"checkbox\" checked=\"checked\"> Task 2</li>\n</ul>", $out);
    }

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

    /**
     * @return array
     */
    public function tableProvider()
    {
        $patterns = array();

        $finder = new \Symfony\Component\Finder\Finder();
        $finder->in(__DIR__.'/Resources/gfm')->files()->name('table-*.md');

        /* @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $name = preg_replace('/\.(md|out)$/', '', $file->getFilename());
            $patterns[] = array(
                $name,
                $file->getContents(),
                trim(file_get_contents(preg_replace('/\.md$/', '.out', $file->getPathname())))
            );
        }

        return $patterns;
    }

    /**
     * @dataProvider tableProvider
     */
    public function testGfmTable($name, $markdown, $expected)
    {
        $md = new \Ciconia\Ciconia();
        $md->addExtension(new \Ciconia\Extension\Gfm\TableExtension());

        $expected = str_replace("\r\n", "\n", $expected);
        $expected = str_replace("\r", "\n", $expected);

        $this->assertEquals($expected, $md->render($markdown), $name);
    }

}