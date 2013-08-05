<?php

namespace Ciconia\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

/**
 * Creates Phar archive
 *
 * Run this command in project root directory
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 *
 * @codeCoverageIgnore
 */
class CompileCommand extends Command
{

    private $fileName = 'ciconia.phar';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('phar')
            ->setDescription('Compiles Ciconia into a phar archive')
            ->addOption('directory', 'o', InputOption::VALUE_REQUIRED, 'Output directory', 'build')
            //->addOption('gzip', null, InputOption::VALUE_NONE)
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (ini_get('phar.readonly')) {
            $output->writeln('<info>PHP option "phar.readonly" must be set to 0.</info>');
            $output->writeln('<info>Try `php -d phar.readonly=0 bin/compile phar</info>');

            return 1;
        }

        if (!is_dir($input->getOption('directory'))) {
            @mkdir($input->getOption('directory'), 0777, true);
        }

        $path = $input->getOption('directory') . DIRECTORY_SEPARATOR . $this->fileName;

        if (file_exists($path)) {
            @unlink($path);
        }

        $output->writeln('Creating Phar...');

        $phar = new \Phar($path, 0, $this->fileName);
        $phar->setSignatureAlgorithm(\Phar::SHA1);
        $phar->startBuffering();

        $finder = Finder::create()
            ->in('.')
            ->files()
            ->name('*.php')
            ->exclude(array('test', 'build', 'bin'))
            ->ignoreVCS(true);

        $count = iterator_count($finder);

        /* @var ProgressHelper $progress */
        $progress = $this->getHelper('progress');
        $progress->start($output, $count);

        /* @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $phar->addFile($file, str_replace('\\', '/', $file->getRelativePathname()));
            $progress->advance();
        }

        $progress->finish();

        $script = file_get_contents('bin/ciconia');
        $script = preg_replace('/^.*?(<\?php.*)/ms', '\1', $script);
        $phar->addFromString('bin/ciconia', $script);

        $phar->setStub($this->getStub());
        $phar->stopBuffering();
        unset($phar);
        chmod($path, 0777);

        $output->writeln('');
        $output->writeln('Build Complete: see ' . $path);

        return 0;
    }

    /**
     * Gets stub
     *
     * @return string
     */
    protected function getStub()
    {
        return "#!/usr/bin/env php\n<?php Phar::mapPhar('ciconia.phar'); require 'phar://ciconia.phar/bin/ciconia'; __HALT_COMPILER();";
    }

}