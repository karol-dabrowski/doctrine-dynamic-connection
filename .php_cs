<?php

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setLineEnding("\n")
    ->setRules([
        '@Symfony' => true,
        '@PSR2' => true,
        '@PhpCsFixer' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_superfluous_phpdoc_tags' => false,
        'braces' => ['allow_single_line_closure' => true],
        'class_definition' => ['single_line' => true],
        'increment_style' => ['style' => 'post'],
        'logical_operators' => true,
        'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
        'blank_line_before_statement' => ['statements' => ['return', 'throw']],
        'php_unit_method_casing' => ['case' => 'snake_case'],
        'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
        'ordered_imports' => false,
        'php_unit_internal_class' => false,
        'ordered_class_elements' => false,
        'php_unit_method_casing' => false,
        'php_unit_test_class_requires_covers' => false,
        'concat_space' => ['spacing' => 'one']
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        ->exclude(['vendor'])
        ->in(__DIR__)
    );