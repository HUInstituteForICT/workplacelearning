<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('./resources')
    ->in(__DIR__)
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
    ])
    ->setFinder($finder)
;
