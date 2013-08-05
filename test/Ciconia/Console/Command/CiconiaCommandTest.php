<?php


use Ciconia\Console\Application;
use Ciconia\Console\Command\CiconiaCommand;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author Kazuyuki Hayashi <>
 */
class CiconiaCommandTest extends \PHPUnit_Framework_TestCase
{

    /**
     * %command.name%
     */
    public function testCommandWithNoArgument()
    {
        $tester = $this->createCommandTester();
        $tester->execute(array());

        $this->assertRegExp('/No input file/', $tester->getDisplay());
    }

    /**
     * %command.name% foo
     */
    public function testCommandWithInvalidArgument()
    {
        $tester = $this->createCommandTester();
        $tester->execute(array('file' => 'foo'));

        $this->assertRegExp('/The input file "foo" not found/', $tester->getDisplay());
    }

    /**
     * %command.name% test/Ciconia/Resources/markdown/2-paragraphs-hard-return.md
     */
    public function testCommandWithNoOption()
    {
        $file = __DIR__ . '/../../Resources/markdown/2-paragraphs-hard-return.md';
        $expected = preg_replace('/\r?\n/', "\n", file_get_contents(__DIR__ . '/../../Resources/markdown/2-paragraphs-hard-return.out'));

        $tester = $this->createCommandTester();
        $tester->execute(array('file' => $file));

        $this->assertEquals($expected, $tester->getDisplay(true));
    }

    /**
     * %command.name% --gfm test/Ciconia/Resources/markdown//2-paragraphs-line.md
     */
    public function testCommandWithGfmOption()
    {
        $file = __DIR__ . '/../../Resources/markdown/2-paragraphs-line.md';
        $expected = preg_replace('/\r?\n/', "\n", file_get_contents(__DIR__ . '/../../Resources/markdown/2-paragraphs-line.out'));

        $tester = $this->createCommandTester();
        $tester->execute(array('file' => $file, '--gfm' => true));

        $this->assertEquals($expected, $tester->getDisplay(true));
    }

    /**
     * %command.name% --format=xhtml test/Ciconia/Resources/markdown//2-paragraphs-line.md
     */
    public function testCommandWithFormatOption()
    {
        $file = __DIR__ . '/../../Resources/markdown/2-paragraphs-line.md';
        $expected = preg_replace('/\r?\n/', "\n", file_get_contents(__DIR__ . '/../../Resources/markdown/2-paragraphs-line.out'));

        $tester = $this->createCommandTester();
        $tester->execute(array('file' => $file, '--format' => 'xhtml'));

        $this->assertEquals($expected, $tester->getDisplay(true));
    }

    /**
     * %command.name% --compress test/Ciconia/Resources/markdown//2-paragraphs-line.md
     */
    public function testCommandWithCompressOption()
    {
        $file = __DIR__ . '/../../Resources/markdown/2-paragraphs-line-returns.md';
        $expected = preg_replace('/\r?\n/', '', file_get_contents(__DIR__ . '/../../Resources/markdown/2-paragraphs-line-returns.out'));

        $tester = $this->createCommandTester();
        $tester->execute(array('file' => $file, '--compress' => true));

        $this->assertEquals($expected, $tester->getDisplay(true));
    }

    /**
     * @return CommandTester
     */
    protected function createCommandTester()
    {
        $application = new Application();
        $application->add(new CiconiaCommand());
        $command = $application->find('ciconia');

        return new CommandTester($command);
    }

}