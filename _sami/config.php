<?php

use Sami\Sami;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('Resources')
    ->in('_src')
;

return new Sami($iterator, array(
    'theme'     => 'ciconia',
    'title'     => 'Test API',
    'build_dir' => __DIR__.'/build',
    'cache_dir' => __DIR__.'/cache',
    'default_opened_level' => 2,
    'template_dirs' => [__DIR__.'/theme']
));