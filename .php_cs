<?php

//list of fixers: https://github.com/FriendsOfPHP/PHP-CS-Fixer

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => [
            'syntax' => 'short',
            ],
        'phpdoc_align' => false,
        'return_type_declaration' => [
            'space_before' => 'one',
        ],
        'phpdoc_no_empty_return' => false,
        'concat_space' => [
            'spacing' => 'one',
        ],
        'yoda_style' => false,
        'no_empty_phpdoc' => true,
        'native_function_invocation' => true,
        'fully_qualified_strict_types' => true,
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
        ],
    ])
    ->setFinder($finder);
