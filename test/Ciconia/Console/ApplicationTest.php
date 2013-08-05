<?php


use Ciconia\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{

    public function testIsApplicationRunAsASingleCommand()
    {
        $application = new Application();
        $application->doRun(new ArrayInput(array()), new \Symfony\Component\Console\Output\NullOutput());
    }

}