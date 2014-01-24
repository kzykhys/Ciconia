<?php

use Ciconia\Ciconia;
use Ciconia\Renderer\XhtmlRenderer;
use Symfony\Component\Finder\Finder;

/**
 * Tests Ciconia\Extensions\Html\*
 *
 * @group Html
 *
 * @author Kazuyuki Hayashi <hayashi@siance.co.jp>
 */
class HtmlExtensionsTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider htmlProvider
     */
    public function testHtmlPatterns($name, $textile, $expected)
    {
        $ciconia = new Ciconia(new XhtmlRenderer());
        $ciconia->addExtensions([
            new \Ciconia\Extension\Html\AttributesExtension()
        ]);

        $expected = str_replace("\r\n", "\n", $expected);
        $expected = str_replace("\r", "\n", $expected);
        $html     = $ciconia->render($textile);

        $this->assertEquals($expected, $html, sprintf('%s failed', $name));
    }

    /**
     * @return array
     */
    public function htmlProvider()
    {
        $finder = Finder::create()
            ->in([__DIR__.'/../Resources/html', __DIR__.'/../Resources/core'])
            ->files()
            ->name('*.md')
            ->notName('link-automatic-email.md');

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