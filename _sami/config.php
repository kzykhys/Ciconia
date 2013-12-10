<?php

use Sami\Sami;
use Sami\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('Resources')
    ->in($dir = '_src/Ciconia/src')
;

$versions = GitVersionCollection::create($dir)
    ->add('1.0', '0.1 branch')
    ->add('master', 'master branch')
;

return new Sami($iterator, array(
    'theme'     => 'ciconia',
    'title'     => 'API',
    'versions'  => $versions,
    'build_dir' => __DIR__.'/../build/%version%',
    'cache_dir' => __DIR__.'/../cache/%version%',
    'default_opened_level' => 2,
    'template_dirs' => [__DIR__.'/theme']
));