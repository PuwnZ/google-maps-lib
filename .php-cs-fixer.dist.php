<?php

//list of fixers: https://github.com/FriendsOfPHP/PHP-CS-Fixer

$finder = PhpCsFixer\Finder::create()
                           ->in(__DIR__ . '/src')
                           ->in(__DIR__ . '/tests');

return (new PhpCsFixer\Config())
                        ->setRules([
                            '@Symfony' => true,
                            'array_indentation' => true,
                            'array_syntax' => [
                                'syntax' => 'short',
                            ],
                            'phpdoc_align' => false,
                            'return_type_declaration' => [
                                'space_before' => 'none',
                            ],
                            'phpdoc_no_empty_return' => false,
                            'concat_space' => [
                                'spacing' => 'one',
                            ],
                            'yoda_style' => false,
                            'no_empty_phpdoc' => true,
                            'native_function_invocation' => true,
                            'fully_qualified_strict_types' => true,
                            'single_blank_line_before_namespace' => true,
                            'php_unit_test_case_static_method_calls' => ['call_type' => 'self'],
                            'declare_strict_types' => true,
                            'header_comment' => [
                                'header' => ''
                            ],
                            'visibility_required' => true,
                            'blank_line_before_statement' => [
                                'statements' => [
                                    'break',
                                    'continue',
                                    'do',
                                    'exit',
                                    'goto',
                                    'if',
                                    'switch',
                                    'throw',
                                    'try',
                                    'yield',
                                    'declare',
                                    'return',
                                    'foreach',
                                ]
                            ],
                            'method_argument_space' => [
                                'on_multiline' => 'ensure_fully_multiline',
                            ],
                            'is_null' => true,
                            'modernize_types_casting' => true,
                            'combine_consecutive_issets' => true,
                            'nullable_type_declaration_for_default_null_value' => true,
                            'psr_autoloading' => true,
                            'strict_comparison' => true,
                            'ternary_to_elvis_operator' => true,
                            'standardize_not_equals' => true,
                            'void_return' => true,
                            'return_assignment' => true,
                            'single_line_after_imports' => true
                        ])
                        ->setFinder($finder);
