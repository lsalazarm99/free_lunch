<?php

/** @noinspection DevelopmentDependenciesUsageInspection */
return (new PhpCsFixer\Config())
    ->setRules(
        [
            '@PSR12' => true,
            '@PHP81Migration' => true,
            '@PhpCsFixer' => true,
            'concat_space' => ['spacing' => 'one'],
            'phpdoc_to_comment' => false,
            'yoda_style' => false,
        ]
    )
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
            ->exclude(
                [
                    'bootstrap/cache',
                    'config',
                    'public',
                    'resources/lang',
                    'storage',
                    'vendor',
                ],
            )
            ->notPath(
                [
                    'server.php',
                ],
            )
    )
    ;
