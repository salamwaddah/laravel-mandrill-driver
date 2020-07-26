<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->name('*.php')
    ->in(__DIR__)
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        'unary_operator_spaces' => false,
        'yoda_style' => false,
        'phpdoc_align' => false,
        'concat_space' => false,
        'combine_consecutive_unsets' => true,
        'no_useless_else' => true,
        'ordered_class_elements' => true,
        'no_useless_return' => true,
        'array_syntax' => array('syntax' => 'short'),
        'no_closing_tag' => true,
        'single_line_after_imports' => false,
        'no_superfluous_phpdoc_tags' => false
    ])
    ->setFinder($finder)
;
