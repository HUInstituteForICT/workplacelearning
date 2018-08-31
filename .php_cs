<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('./resources')
    ->exclude('./storage')
    ->exclude('./vendor')
    ->exclude('./node_modules')
    ->in(__DIR__)
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
    ])
    ->setFinder($finder)
;
