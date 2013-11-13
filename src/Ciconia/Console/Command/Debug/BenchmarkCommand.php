<?php

namespace Ciconia\Console\Command\Debug;

use Ciconia\Ciconia;
use Ciconia\Markdown;
use Ciconia\Renderer\HtmlRenderer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Benchmark
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class BenchmarkCommand extends Command
{

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('benchmark')
            ->setDescription('')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var \Symfony\Component\Console\Helper\TableHelper $table */
        $table = $this->getHelper('table');

        $stopwatch = new Stopwatch();

        $stopwatch->start('benchmark', 'emitter');
        $this->testEmitter();
        $event = $stopwatch->stop('benchmark');
        $table->addRow([
            'Emitter', $event->getDuration()
        ]);

        $stopwatch->start('benchmark', 'parser');
        $this->testParser();
        $event = $stopwatch->stop('benchmark');
        $table->addRow([
            'Parser', $event->getDuration()
        ]);

        $table->render($output);
    }

    private function testEmitter()
    {
        $emitter = new Markdown(new HtmlRenderer());

        for ($i = 0; $i < 10000; $i++) {
            $emitter->on('event1', function () {}, $i * ($i % 2 == 0 ? 1 : -1));
        }

        $emitter->emit('event1', []);
    }

    private function testParser()
    {
        $ciconia = new Ciconia();

        for ($i = 0; $i < 1000; $i++) {
            $ciconia->render('');
        }
    }

} 