<?php

namespace Ciconia\Console\Command;

use Ciconia\Ciconia;
use Ciconia\Extension\Gfm\FencedCodeBlockExtension;
use Ciconia\Extension\Gfm\InlineStyleExtension;
use Ciconia\Extension\Gfm\TaskListExtension;
use Ciconia\Extension\Gfm\WhiteSpaceExtension;
use Ciconia\Renderer\XhtmlRenderer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command-line interface
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class CiconiaCommand extends Command
{

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('ciconia')
            ->setDescription('Translates markdown into HTML and displays to STDOUT')
            ->addArgument('file', InputArgument::OPTIONAL, 'The input file')
            ->addOption('gfm', null, InputOption::VALUE_NONE, 'Activate Gfm extensions')
            ->addOption('compress', 'c', InputOption::VALUE_NONE, 'Remove whitespace between HTML tags')
            //->addOption('profile', null, InputOption::VALUE_NONE, 'Display events and extensions information')
            ->addOption('format', 'f', InputOption::VALUE_OPTIONAL, 'Output format (html|xhtml)', 'html')
            ->setHelp($this->getHelpContent())
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $content = $this->handleInput($input);
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            $this->runHelp($input, $output);

            return 1;
        }

        $ciconia = new Ciconia();

        if ($input->getOption('format') == 'xhtml') {
            $ciconia->setRenderer(new XhtmlRenderer());
        }

        if ($input->getOption('gfm')) {
            $ciconia->addExtensions(array(
                new FencedCodeBlockExtension(),
                new InlineStyleExtension(),
                new TaskListExtension(),
                new WhiteSpaceExtension()
            ));
        }

        $html = $ciconia->render($content);

        if ($input->getOption('compress')) {
            $html = preg_replace('/>\s+</', '><', $html);
        }

        $output->write($html, false, OutputInterface::OUTPUT_RAW);

        return 0;
    }

    protected function handleInput(InputInterface $input)
    {
        if ($file = $input->getArgument('file')) {
            if (!file_exists($file)) {
                throw new \InvalidArgumentException(sprintf('The input file "%s" not found', $file));
            }

            return file_get_contents($file);
        } else {
            $contents = '';

            if ($stdin = fopen('php://stdin', 'r')) {
                // Warning: stream_set_blocking always fails on Windows
                if (stream_set_blocking($stdin, false)) {
                    $contents = stream_get_contents($stdin);
                }

                fclose($stdin);
            }

            if ($contents) {
                return $contents;
            }
        }

        throw new \InvalidArgumentException('No input file');
    }

    /**
     * Runs help command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function runHelp(InputInterface $input, OutputInterface $output)
    {
        /* @var HelpCommand $help */
        $help = $this->getApplication()->find('help');
        $help->setCommand($this);
        $help->run($input, $output);
    }

    /**
     * @return string
     */
    protected function getHelpContent()
    {
        return <<< EOT
Translates markdown into html and displays to STDOUT
  <info>%command.name% /path/to/file.md</info>

Following command saves result to file
  <info>%command.name% /path/to/file.md > /path/to/file.html</info>

Or using pipe (On Windows in does't work)
  <info>echo "Markdown is **awesome**" | %command.name%</info>
EOT;
    }

}