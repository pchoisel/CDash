<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('app/cdash/config')
    ->exclude('bootstrap/cache')
    ->exclude('node_modules')
    ->exclude('storage')
    ->exclude('vendor')
    ->in(__DIR__)
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        '@PSR2' => true,
        'method_argument_space' => ['on_multiline' => 'ignore'],
    ])
    ->setFinder($finder)
;