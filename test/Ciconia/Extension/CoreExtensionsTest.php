<?php

use Ciconia\Ciconia;
use Ciconia\Renderer\XhtmlRenderer;
use Symfony\Component\Finder\Finder;

/**
 * Tests Ciconia\Extensions\Core\*
 *
 * @author Kazuyuki Hayashi <hayashi@siance.co.jp>
 */
class CoreExtensionsTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test core patterns
     *
     * See `test/Resources/markdown-testsuite`
     *
     * @param string $name     Name of the test case
     * @param string $markdown The Markdown content
     * @param string $expected Expected output
     *
     * @dataProvider markdownTestSuiteProvider
     */
    public function testWithMarkdownTestSuite($name, $markdown, $expected)
    {
        $ciconia = new Ciconia(new XhtmlRenderer());
        $html    = $ciconia->render($markdown);

        $this->assertEquals($expected, $html, sprintf('%s failed', $name));
    }

    /**
     * Test an email link
     */
    public function testAutoLinkEmail()
    {
        $ciconia  = new Ciconia();
        $markdown = 'Email <test@example.com>';
        $output   = $ciconia->render($markdown);
        $expected = '<p>Email <a href="mailto:test@example.com">test@example.com</a></p>';

        $this->assertEquals($expected, html_entity_decode($output));
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
        $html    = $ciconia->render($markdown, ['strict' => true]);

        $this->assertEquals($expected, $html, sprintf('%s failed', $name));
    }

    /**
     * @return array
     */
    public function markdownTestSuiteProvider()
    {
        $finder = Finder::create()
            ->in(__DIR__.'/../Resources/core')
            ->files()
            ->name('*.md')
            ->notName('link-automatic-email.md');

        return $this->processPatterns($finder);
    }

    /**
     * @return array
     */
    public function strictModeProvider()
    {
        $finder = Finder::create()
            ->in(__DIR__.'/../Resources/options/strict/core')
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