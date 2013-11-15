<?php

use Ciconia\Ciconia;
use Ciconia\Extension\Gfm\FencedCodeBlockExtension;
use Ciconia\Extension\Gfm\InlineStyleExtension;
use Ciconia\Extension\Gfm\TableExtension;
use Ciconia\Extension\Gfm\TaskListExtension;
use Ciconia\Extension\Gfm\UrlAutoLinkExtension;
use Ciconia\Extension\Gfm\WhiteSpaceExtension;
use Symfony\Component\Finder\Finder;

/**
 * Tests Ciconia\Extensions\Gfm\*
 *
 * @author Kazuyuki Hayashi <hayashi@siance.co.jp>
 */
class GfmExtensionsTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test all gfm patterns
     *
     * See `test/Resources/gfm`
     *
     * @param string $name     Name of the test case
     * @param string $markdown The Markdown content
     * @param string $expected Expected output
     *
     * @dataProvider gfmProvider
     */
    public function testGfmPatterns($name, $markdown, $expected)
    {
        $ciconia = new Ciconia();
        $ciconia->addExtensions([
            new FencedCodeBlockExtension(),
            new InlineStyleExtension(),
            new WhiteSpaceExtension(),
            new TaskListExtension(),
            new TableExtension(),
            new UrlAutoLinkExtension()
        ]);

        $expected = str_replace("\r\n", "\n", $expected);
        $expected = str_replace("\r", "\n", $expected);
        $html     = $ciconia->render($markdown);

        $this->assertEquals($expected, $html, sprintf('%s failed', $name));
    }

    /**
     * On strict mode
     *
     * @param string $name     Name of the test case
     * @param string $markdown The Markdown content
     * @param string $expected Expected output
     *
     * @dataProvider strictModeProvider
     * @expectedException \Ciconia\Exception\SyntaxError
     */
    public function testStrictMode($name, $markdown, $expected)
    {
        $ciconia = new Ciconia();
        $ciconia->addExtensions([
            new FencedCodeBlockExtension(),
            new InlineStyleExtension(),
            new WhiteSpaceExtension(),
            new TaskListExtension(),
            new TableExtension(),
            new UrlAutoLinkExtension()
        ]);

        $html = $ciconia->render($markdown, ['strict' => true]);

        $this->assertEquals($expected, $html, sprintf('%s failed', $name));
    }

    /**
     * @return array
     */
    public function gfmProvider()
    {
        $finder = Finder::create()
            ->in(__DIR__.'/../Resources/gfm')
            ->files()
            ->name('*.md');

        return $this->processPatterns($finder);
    }

    /**
     * @return array
     */
    public function strictModeProvider()
    {
        $finder = Finder::create()
            ->in(__DIR__.'/../Resources/options/strict/gfm')
            ->files()
            ->name('*.md');

        return $this->processPatterns($finder);
    }

    /**
     * @param Finder|\Symfony\Component\Finder\SplFileInfo[] $finder
     *
     * @return array
     */
    protected function processPatterns(Finder $finder)
    {
        $patterns = [];

        foreach ($finder as $file) {
            $name       = preg_replace('/\.(md|out)$/', '', $file->getFilename());
            $expected   = trim(file_get_contents(preg_replace('/\.md$/', '.out', $file->getPathname())));
            $expected   = str_replace("\r\n", "\n", $expected);
            $expected   = str_replace("\r", "\n", $expected);
            $patterns[] = [$name, $file->getContents(), $expected];
        }

        return $patterns;
    }

} 