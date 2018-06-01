<?php

use Sami\Sami;
use Sami\Parser\Filter\TrueFilter;
use Sami\Parser\Filter\PublicFilter;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in(['src'])
    ;

return new Sami($iterator, [
    'title' => 'Laravel Guzzle',
    'theme' => 'default',
    'include_parent_data' => true,
    'insert_todos' => true,
    'build_dir' =>  __DIR__.'/docs/%version%/api',
    'cache_dir' =>  __DIR__.'/.cache/sami/%version%',
    'default_opened_level' => 2,
    'sort_class_properties' => true,
    'sort_class_methods' => true,
    'sort_class_constants' => true,
    'sort_class_traits' => true,
    'sort_class_interfaces' => true,
    /**
     * A not about the Filter classes:
     *  - PublicFilter will only display public methods/properties in the documentation
     *  - TrueFilter will display all methods/properties, including protected/private
     */
    'filter' => function () {
        return new TrueFilter();
    },
]);
